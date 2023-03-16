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
use JBZoo\CIReportConverter\Formats\MetricMaps\PhpLoc;

use function JBZoo\Data\json;

/**
 * Class PhpLocStatsTcConverter
 * @package JBZoo\CIReportConverter\Converters
 */
class PhpLocStatsTcConverter extends AbstractStatsTcConverter
{
    public const TYPE = 'phploc-json';
    public const NAME = 'PHPloc (json)';

    /**
     * @inheritDoc
     */
    public function toInternalMetric(string $sourceCode): Metrics
    {
        $data = json($sourceCode)->getArrayCopy();
        return self::buildMetrics($data, new PhpLoc());
    }
}
