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
use JBZoo\CiReportConverter\Formats\MetricMaps\PhpMetrics;
use JBZoo\CiReportConverter\Formats\Xml;

use function JBZoo\Data\data;

/**
 * Class PhpMetricsStatsTcConverter
 * @package JBZoo\CiReportConverter\Converters
 */
class PhpMetricsStatsTcConverter extends AbstractStatsTcConverter
{
    public const TYPE = 'phpmetrics-xml';
    public const NAME = 'PHP Metrics (xml)';

    /**
     * @inheritDoc
     */
    public function toInternalMetric(string $sourceCode): Metrics
    {
        $xmlAsArray = Xml::dom2Array(Xml::createDomDocument($sourceCode));

        $attrs = data($xmlAsArray)->findArray('_children.0._attrs');
        unset(
            $attrs['generated'],
            $attrs['pdepend']
        );

        return self::buildMetrics($attrs, new PhpMetrics());
    }
}
