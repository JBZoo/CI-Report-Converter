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
use JBZoo\ToolboxCI\Formats\MetricMaps\PhpLoc;

use function JBZoo\Data\json;

/**
 * Class PhpLocStatsTcConverter
 * @package JBZoo\ToolboxCI\Converters
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
