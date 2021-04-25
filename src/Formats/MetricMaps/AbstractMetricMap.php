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

namespace JBZoo\ToolboxCI\Formats\MetricMaps;

/**
 * Class AbstractMetricMap
 * @package JBZoo\ToolboxCI\Formats\MetricMaps
 */
abstract class AbstractMetricMap
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string[]
     */
    protected $map = [];

    /**
     * @return string[]
     */
    public function getMap(): array
    {
        return $this->map;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
