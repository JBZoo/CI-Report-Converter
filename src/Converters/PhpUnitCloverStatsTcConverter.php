<?php

/**
 * JBZoo Toolbox - CI-Report-Converter.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/CI-Report-Converter
 */

declare(strict_types=1);

namespace JBZoo\CIReportConverter\Converters;

use JBZoo\CIReportConverter\Formats\Metric\Metrics;
use JBZoo\CIReportConverter\Formats\MetricMaps\PhpUnitClover;

use function JBZoo\Data\data;
use function JBZoo\Utils\float;

final class PhpUnitCloverStatsTcConverter extends AbstractStatsTcConverter
{
    public const TYPE = 'phpunit-clover-xml';
    public const NAME = 'PHPUnit Clover (xml)';

    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function toInternalMetric(string $sourceCode): Metrics
    {
        $cloverXml = new \SimpleXMLElement($sourceCode);
        $info      = data((array)$cloverXml->project?->metrics)->getSelf('@attributes');

        $coveredClasses = 0;

        $nodeClasses = $cloverXml->xpath('//class');
        // @phpstan-ignore-next-line
        if (\is_iterable($nodeClasses)) {
            foreach ($nodeClasses as $class) {
                if ($class->metrics === null) {
                    continue;
                }

                if ((int)$class->metrics['coveredmethods'] === (int)$class->metrics['methods']) {
                    $coveredClasses++;
                }
            }
        }

        $data = [
            'Files'                   => $info->getInt('files'),
            'LinesOfCode'             => $info->getInt('loc'),
            'NonCommentLinesOfCode'   => $info->getInt('ncloc'),
            'CodeCoverageAbsLTotal'   => $info->getInt('elements'),
            'CodeCoverageAbsLCovered' => $info->getInt('coveredelements'),
            'CodeCoverageAbsBTotal'   => $info->getInt('statements'),
            'CodeCoverageAbsBCovered' => $info->getInt('coveredstatements'),
            'CodeCoverageAbsMTotal'   => $info->getInt('methods'),
            'CodeCoverageAbsMCovered' => $info->getInt('coveredmethods'),
            'CodeCoverageAbsCTotal'   => $info->getInt('classes'),
            'CodeCoverageAbsCCovered' => $coveredClasses,
            'CodeCoverageB'           => self::percent($info->getInt('coveredstatements'), $info->getInt('statements')),
            'CodeCoverageL'           => self::percent($info->getInt('coveredelements'), $info->getInt('elements')),
            'CodeCoverageM'           => self::percent($info->getInt('coveredmethods'), $info->getInt('methods')),
            'CodeCoverageC'           => self::percent($coveredClasses, $info->getInt('classes')),
        ];

        $crapValues = [];
        $crapAmount = 0;

        $allCrapAttrs = $cloverXml->xpath('//@crap');
        // @phpstan-ignore-next-line
        if (\is_iterable($allCrapAttrs)) {
            foreach ($allCrapAttrs as $crap) {
                $crapValues[] = float($crap);
                $crapAmount++;
            }
        }

        $crapValuesCount = \count($crapValues) > 0 ? \count($crapValues) : 1;
        $crapSummary     = \max(\array_sum($crapValues), 0);

        $data['CRAPTotal']   = $crapSummary;
        $data['CRAPAmount']  = $crapAmount;
        $data['CRAPMaximum'] = \count($crapValues) > 0 ? \max($crapValues) : 0.0;
        $data['CRAPAverage'] = self::percent($crapSummary, $crapValuesCount) / 100;
        $data['CRAPPercent'] = self::percent($crapAmount, $crapValuesCount);

        return self::buildMetrics($data, new PhpUnitClover());
    }

    private static function percent(float|int $current, float|int $total): float
    {
        if ($total <= 0) {
            $total = 1;
        }

        if ($current > 0) {
            return ($current / $total) * 100;
        }

        return 0.0;
    }
}
