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

namespace JBZoo\CIReportConverter\Formats\Metric;

use JBZoo\CIReportConverter\Formats\AbstractNode;
use JBZoo\CIReportConverter\Formats\MetricMaps\AbstractMetricMap;

final class Metrics extends AbstractNode
{
    private array $metrics = [];

    private ?AbstractMetricMap $map = null;

    public function add(string $key, float|int|null $value = null): self
    {
        $key = \trim($key);
        if ($key === '') {
            return $this;
        }

        if (!\array_key_exists($key, $this->metrics)) {
            $this->metrics[$key] = $value;
        }

        return $this;
    }

    public function setMap(AbstractMetricMap $map): self
    {
        $this->map = $map;

        return $this;
    }

    public function toArray(): array
    {
        $metrics = $this->getMetrics();

        $result = [];

        foreach ($metrics as $metric) {
            if ($metric->name !== '') {
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
        $toolName  = '';

        if ($this->map !== null) {
            $columnMap = $this->map->getMap();
            $toolName  = $this->map->getName() . ':';
        }

        $result = [];

        foreach ($this->metrics as $key => $value) {
            if ($value === null) {
                continue;
            }

            $key = (string)$key;

            $metric        = new Metric();
            $metric->key   = $key;
            $metric->value = $value;
            $metric->name  = \array_key_exists($key, $columnMap)
                ? "{$columnMap[$key]} ({$toolName}{$key})"
                : "{$toolName}{$key}";

            $result[$key] = $metric;
        }

        \uasort($result, static fn (Metric $metric1, Metric $metric2): int => \strcmp($metric1->name, $metric2->name));

        return $result;
    }
}
