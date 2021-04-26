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

use function JBZoo\Data\data;

/**
 * Class Factory
 * @package JBZoo\CiReportConverter\Converters
 */
class Factory
{
    /**
     * @param string|false|null $sourceCode
     * @param string            $sourceFormat
     * @param string            $targetFormat
     * @param array             $params
     * @return string
     */
    public static function convert(
        $sourceCode,
        string $sourceFormat,
        string $targetFormat,
        array $params = []
    ): string {
        $params = data($params);

        if ($sourceCode) {
            $sourceCode = Map::getConverter($sourceFormat, Map::INPUT)
                ->setRootPath($params->get('root_path'))
                ->setRootSuiteName($params->get('suite_name'))
                ->toInternal($sourceCode);

            return Map::getConverter($targetFormat, Map::OUTPUT)
                ->setRootPath($params->get('root_path'))
                ->setRootSuiteName($params->get('suite_name'))
                ->setFlowId($params->getInt('flow_id'))
                ->fromInternal($sourceCode);
        }

        return '';
    }

    /**
     * @param string   $sourceCode
     * @param string   $sourceFormat
     * @param int|null $flowId
     * @return string
     */
    public static function convertMetric(string $sourceCode, string $sourceFormat, ?int $flowId = null): string
    {
        $sourceCode = trim($sourceCode);
        if ('' === $sourceCode) {
            return '';
        }

        $tcStatsConverter = Map::getMetric($sourceFormat, $flowId);

        return $tcStatsConverter->fromInternalMetric($tcStatsConverter->toInternalMetric($sourceCode));
    }
}
