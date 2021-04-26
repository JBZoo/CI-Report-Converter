<?php

/**
 * JBZoo Toolbox - CI-Report-Converter
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    CI-Report-Converter
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/CI-Report-Converter
 */

declare(strict_types=1);

namespace JBZoo\CiReportConverter\Converters;

use JBZoo\CiReportConverter\Formats\Metric\Metrics;
use JBZoo\CiReportConverter\Formats\MetricMaps\PhpUnitClover;

use function JBZoo\Data\data;
use function JBZoo\Utils\float;

/**
 * Class PhpUnitCloverStatsTcConverter
 * @package JBZoo\CiReportConverter\Converters
 */
class PhpUnitCloverStatsTcConverter extends AbstractStatsTcConverter
{
    public const TYPE = 'phpunit-clover-xml';
    public const NAME = 'PHPUnit Clover (xml)';

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function toInternalMetric(string $sourceCode): Metrics
    {
        $cloverXml = new \SimpleXMLElement($sourceCode);

        $info = data((array)$cloverXml->project->metrics)->getSelf('@attributes');

        $coveredClasses = 0;

        $nodeClasses = $cloverXml->xpath('//class');
        if (false !== $nodeClasses) {
            foreach ($nodeClasses as $class) {
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
        if (false !== $allCrapAttrs) {
            foreach ($allCrapAttrs as $crap) {
                $crapValues[] = float($crap);
                $crapAmount++;
            }
        }

        $crapValuesCount = count($crapValues) ?: 1;
        $crapSummary = array_sum($crapValues) ?: 0;

        $data['CRAPTotal'] = $crapSummary;
        $data['CRAPAmount'] = $crapAmount;
        /** @phan-suppress-next-line PhanPartialTypeMismatchArgumentInternal */
        $data['CRAPMaximum'] = count($crapValues) > 0 ? max($crapValues) : 0.0;
        $data['CRAPAverage'] = self::percent($crapSummary, $crapValuesCount) / 100;
        $data['CRAPPercent'] = self::percent($crapAmount, $crapValuesCount);

        return self::buildMetrics($data, new PhpUnitClover());
    }

    /**
     * @param float|int $current
     * @param float|int $total
     * @return float
     */
    private static function percent($current, $total): float
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
