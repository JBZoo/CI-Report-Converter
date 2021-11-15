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
use JBZoo\CiReportConverter\Formats\MetricMaps\AbstractMetricMap;
use JBZoo\CiReportConverter\Formats\TeamCity\TeamCity;
use JBZoo\CiReportConverter\Formats\TeamCity\Writers\AbstractWriter;
use JBZoo\CiReportConverter\Formats\TeamCity\Writers\Buffer;

use function JBZoo\Utils\float;
use function JBZoo\Utils\int;

/**
 * Class AbstractStatsTcConverter
 * @package JBZoo\CiReportConverter\Converters
 */
abstract class AbstractStatsTcConverter extends AbstractConverter
{
    /**
     * @var TeamCity
     */
    private $tcLogger;

    /**
     * TeamCityTestsConverter constructor.
     * @param array               $params
     * @param int|null            $flowId
     * @param AbstractWriter|null $tcWriter
     */
    public function __construct(array $params = [], ?int $flowId = null, ?AbstractWriter $tcWriter = null)
    {
        $this->tcLogger = new TeamCity($tcWriter ?: new Buffer(), $flowId, $params);
    }

    /**
     * @param array             $data
     * @param AbstractMetricMap $map
     * @return Metrics
     */
    protected static function buildMetrics(array $data, AbstractMetricMap $map): Metrics
    {
        $metrics = new Metrics();
        $metrics->setMap($map);

        foreach ($data as $key => $value) {
            if (null === $value || '' === $value) {
                continue;
            }

            if (\is_float($value) || \strpos((string)$value, '.') !== false) {
                $metrics->add((string)$key, float($value, 6));
            } else {
                $metrics->add((string)$key, int($value));
            }
        }

        return $metrics;
    }

    /**
     * @param string $sourceCode
     * @return Metrics
     * @phan-suppress PhanUnusedPublicMethodParameter
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toInternalMetric(string $sourceCode): Metrics
    {
        throw new Exception('Method \"' . __METHOD__ . '\" is not available');
    }

    /**
     * @param Metrics $metrics
     * @return string
     */
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
}
