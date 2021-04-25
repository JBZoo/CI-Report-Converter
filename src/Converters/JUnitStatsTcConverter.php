<?php

/**
 * JBZoo Toolbox - Toolbox-CI
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Toolbox-CI
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Toolbox-CI
 */

declare(strict_types=1);

namespace JBZoo\ToolboxCI\Converters;

use JBZoo\ToolboxCI\Formats\Metric\Metrics;
use JBZoo\ToolboxCI\Formats\MetricMaps\JUnit;

/**
 * Class JUnitStatsTcConverter
 * @package JBZoo\ToolboxCI\Converters
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
