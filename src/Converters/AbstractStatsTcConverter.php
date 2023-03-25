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
use JBZoo\CIReportConverter\Formats\MetricMaps\AbstractMetricMap;
use JBZoo\CIReportConverter\Formats\TeamCity\TeamCity;
use JBZoo\CIReportConverter\Formats\TeamCity\Writers\AbstractWriter;
use JBZoo\CIReportConverter\Formats\TeamCity\Writers\Buffer;

use function JBZoo\Utils\float;
use function JBZoo\Utils\int;

abstract class AbstractStatsTcConverter extends AbstractConverter
{
    private TeamCity $tcLogger;

    public function __construct(array $params = [], int $flowId = 0, ?AbstractWriter $tcWriter = null)
    {
        $this->tcLogger = new TeamCity($tcWriter ?? new Buffer(), $flowId, $params);
    }

    /**
     * @phan-suppress PhanUnusedPublicMethodParameter
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toInternalMetric(string $sourceCode): Metrics
    {
        throw new Exception('Method \"' . __METHOD__ . '\" is not available');
    }

    public function fromInternalMetric(Metrics $metrics): string
    {
        foreach ($metrics->getMetrics() as $metric) {
            $this->tcLogger->write('buildStatisticValue', [
                'key'   => $metric->name,
                'value' => $metric->value,
            ]);
        }

        $buffer = $this->tcLogger->getWriter();
        if ($buffer instanceof Buffer) {
            return \implode('', $buffer->getBuffer());
        }

        return '';
    }

    protected static function buildMetrics(array $data, AbstractMetricMap $map): Metrics
    {
        $metrics = new Metrics();
        $metrics->setMap($map);

        foreach ($data as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            if (\is_float($value) || \str_contains((string)$value, '.')) {
                $metrics->add((string)$key, float($value, 6));
            } else {
                $metrics->add((string)$key, int($value));
            }
        }

        return $metrics;
    }
}
