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

namespace JBZoo\CiReportConverter\Formats\MetricMaps;

/**
 * Class AbstractMetricMap
 * @package JBZoo\CiReportConverter\Formats\MetricMaps
 */
abstract class AbstractMetricMap
{
    /**
     * @var string
     */
    protected string $name = '';

    /**
     * @var string[]
     */
    protected array $map = [];

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
