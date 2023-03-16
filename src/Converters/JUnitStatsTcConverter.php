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
use JBZoo\CIReportConverter\Formats\MetricMaps\JUnit;

/**
 * Class JUnitStatsTcConverter
 * @package JBZoo\CIReportConverter\Converters
 */
class JUnitStatsTcConverter extends AbstractStatsTcConverter
{
    public const TYPE = 'junit-xml';
    public const NAME = 'JUnit.xml';

    /**
     * @inheritDoc
     */
    public function toInternalMetric(string $sourceCode): Metrics
    {
        $sourceCode = (new JUnitConverter())->toInternal($sourceCode);

        $attrs = [
            'time'       => $sourceCode->getTime(),
            'tests'      => $sourceCode->getCasesCount(),
            'assertions' => $sourceCode->getAssertionsCount(),
            'errors'     => $sourceCode->getErrorsCount(),
            'warnings'   => $sourceCode->getWarningCount(),
            'failure'    => $sourceCode->getFailureCount(),
            'skipped'    => $sourceCode->getSkippedCount(),
        ];

        return self::buildMetrics($attrs, new JUnit());
    }
}
