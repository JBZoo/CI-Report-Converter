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
use JBZoo\CIReportConverter\Formats\MetricMaps\PhpDepend;
use JBZoo\CIReportConverter\Formats\Xml;

use function JBZoo\Data\data;

final class PhpDependStatsTcConverter extends AbstractStatsTcConverter
{
    public const TYPE = 'pdepend-xml';
    public const NAME = 'PHP Depend (xml)';

    public function toInternalMetric(string $sourceCode): Metrics
    {
        $xmlAsArray = Xml::dom2Array(Xml::createDomDocument($sourceCode));

        $attrs = data($xmlAsArray)->findArray('_children.0._attrs');
        unset(
            $attrs['generated'],
            $attrs['pdepend'],
        );

        return self::buildMetrics($attrs, new PhpDepend());
    }
}
