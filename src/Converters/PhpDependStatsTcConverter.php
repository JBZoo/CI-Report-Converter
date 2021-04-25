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
use JBZoo\ToolboxCI\Formats\MetricMaps\PhpDepend;
use JBZoo\ToolboxCI\Formats\Xml;

use function JBZoo\Data\data;

/**
 * Class PhpDependStatsTcConverter
 * @package JBZoo\ToolboxCI\Converters
 */
class PhpDependStatsTcConverter extends AbstractStatsTcConverter
{
    public const TYPE = 'pdepend-xml';
    public const NAME = 'PHP Depend (xml)';

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

        return self::buildMetrics($attrs, new PhpDepend());
    }
}
