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

namespace JBZoo\CiReportConverter\Formats\Metric;

use JBZoo\CiReportConverter\Formats\AbstractNode;
use JBZoo\CiReportConverter\Formats\MetricMaps\AbstractMetricMap;

/**
 * Class Metrics
 * @package JBZoo\CiReportConverter\Formats\Metric
 */
class Metrics extends AbstractNode
{
    /**
     * @var float[]|int[]|null[]
     */
    private $metrics = [];

    /**
     * @var AbstractMetricMap|null
     */
    private $map;

    /**
     * @param string         $key
     * @param float|int|null $value
     * @return $this
     */
    public function add(string $key, $value = null): self
    {
        $key = trim($key);
        if ($key === '') {
            return $this;
        }

        if (!array_key_exists($key, $this->metrics)) {
            $this->metrics[$key] = $value;
        }

        return $this;
    }

    /**
     * @param AbstractMetricMap $map
     * @return $this
     */
    public function setMap(AbstractMetricMap $map): self
    {
        $this->map = $map;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $metrics = $this->getMetrics();

        $result = [];
        foreach ($metrics as $metric) {
            if ($metric->name) {
                $result[$metric->name] = $metric->toArray();
            }
        }

        return $result;
    }

    /**
     * @return Metric[]
     */
    public function getMetrics(): array
    {
        $columnMap = [];
        if (null !== $this->map) {
            $columnMap = $this->map->getMap();
        }

        $toolName = $this->map ? $this->map->getName() . ':' : '';

        $result = [];
        foreach ($this->metrics as $key => $value) {
            if (null === $value) {
                continue;
            }

            $key = (string)$key;

            $metric = new Metric();
            $metric->key = $key;
            $metric->value = $value;
            $metric->name = array_key_exists($key, $columnMap)
                ? "{$columnMap[$key]} ({$toolName}{$key})"
                : "{$toolName}{$key}";

            $result[$key] = $metric;
        }

        uasort($result, static function (Metric $metric1, Metric $metric2): int {
            return strcmp((string)$metric1->name, (string)$metric2->name);
        });

        return $result;
    }
}
