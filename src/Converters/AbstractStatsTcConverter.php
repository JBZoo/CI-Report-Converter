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
use JBZoo\ToolboxCI\Formats\MetricMaps\AbstractMetricMap;
use JBZoo\ToolboxCI\Formats\TeamCity\TeamCity;
use JBZoo\ToolboxCI\Formats\TeamCity\Writers\AbstractWriter;
use JBZoo\ToolboxCI\Formats\TeamCity\Writers\Buffer;

use function JBZoo\Utils\float;
use function JBZoo\Utils\int;

/**
 * Class AbstractStatsTcConverter
 * @package JBZoo\ToolboxCI\Converters
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

            if (is_float($value) || strpos((string)$value, '.') !== false) {
                $metrics->add($key, float($value, 6));
            } else {
                $metrics->add($key, int($value));
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
            return implode('', $buffer->getBuffer());
        }

        return '';
    }
}
