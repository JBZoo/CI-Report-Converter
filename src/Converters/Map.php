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

use JBZoo\Markdown\Table;

/**
 * Class Map
 * @package JBZoo\CIReportConverter\Converters
 */
class Map
{
    public const INPUT  = 'input';
    public const OUTPUT = 'output';

    public const MAP_TESTS = [
        JUnitConverter::class               => [self::INPUT => true, self::OUTPUT => true],
        TeamCityTestsConverter::class       => [self::INPUT => false, self::OUTPUT => true],
        PhpMdJsonConverter::class           => [self::INPUT => true, self::OUTPUT => false],
        CheckStyleConverter::class          => [self::INPUT => true, self::OUTPUT => false],
        PsalmJsonConverter::class           => [self::INPUT => true, self::OUTPUT => false],
        TeamCityInspectionsConverter::class => [self::INPUT => false, self::OUTPUT => true],
        PhpMndConverter::class              => [self::INPUT => true, self::OUTPUT => false],
        GithubCliConverter::class           => [self::INPUT => false, self::OUTPUT => true],
        PmdCpdConverter::class              => [self::INPUT => true, self::OUTPUT => false],
        GitLabJsonConverter::class          => [self::INPUT => false, self::OUTPUT => true],
        PlainTextConverter::class           => [self::INPUT => false, self::OUTPUT => true],
    ];

    public const MAP_METRICS = [
        PhpLocStatsTcConverter::class,
        PhpDependStatsTcConverter::class,
        PhpMetricsStatsTcConverter::class,
        PhpUnitCloverStatsTcConverter::class,
        JUnitStatsTcConverter::class,
    ];

    /**
     * @return array
     */
    public static function getTable(): array
    {
        $result = [];

        $drivers = \array_keys(self::MAP_TESTS);
        \sort($drivers);

        foreach ($drivers as $source) {
            foreach ($drivers as $target) {
                $sourceName = $source::NAME;
                $targetName = $target::TYPE;
                $result[$sourceName][$targetName] = self::isAvailable($source, $target);
            }
        }

        return $result;
    }

    /**
     * @param string|null $direction
     * @return array
     */
    public static function getAvailableFormats(?string $direction = null): array
    {
        $drivers = \array_keys(self::MAP_TESTS);
        \sort($drivers);

        if (null !== $direction) {
            return \array_filter(\array_map(static function (string $converterClass) use ($direction): ?string {
                if (self::MAP_TESTS[$converterClass][$direction]) {
                    return $converterClass::TYPE;
                }
                return null;
            }, $drivers));
        }

        return \array_map(static function (string $converterClass): string {
            return $converterClass::TYPE;
        }, $drivers);
    }

    /**
     * @return array
     */
    public static function getAvailableMetrics(): array
    {
        $result = [];
        foreach (self::MAP_METRICS as $driver) {
            $result[] = $driver::TYPE;
        }

        \sort($result);

        return $result;
    }

    /**
     * @param string $source
     * @param string $target
     * @return bool
     */
    public static function isAvailable(string $source, string $target): bool
    {
        return self::MAP_TESTS[$source][self::INPUT] && self::MAP_TESTS[$target][self::OUTPUT];
    }

    /**
     * @return string
     */
    public static function getMarkdownTable(): string
    {
        $tableData = self::getTable();
        $header = \array_keys($tableData);

        $rows = [];
        foreach ($tableData as $key => $info) {
            $rows[$key] = \array_values(\array_map(static function (bool $value) {
                return $value ? 'Yes' : '-';
            }, $info));

            \array_unshift($rows[$key], $key);
        }

        \array_unshift($header, 'Source/Target');

        return (new Table())
            ->setHeaders($header)
            ->appendRows($rows)
            ->render();
    }

    /**
     * @param string $format
     * @param string $direction
     * @return AbstractConverter
     */
    public static function getConverter(string $format, string $direction): AbstractConverter
    {
        /** @var AbstractConverter $class */
        /** @var array $options */
        foreach (self::MAP_TESTS as $class => $options) {
            if ($class::TYPE === $format && $options[$direction]) {
                return new $class();
            }
        }

        throw new Exception(
            "The format \"{$format}\" is not available as \"{$direction}\" direction. " .
            "See `ci-report-converter convert:map`"
        );
    }

    /**
     * @param string   $sourceFormat
     * @param int|null $flowId
     * @return AbstractStatsTcConverter
     */
    public static function getMetric(string $sourceFormat, ?int $flowId = null): AbstractStatsTcConverter
    {
        foreach (self::MAP_METRICS as $class) {
            if ($class::TYPE === $sourceFormat) {
                return new $class([], $flowId);
            }
        }

        throw new Exception("The format \"{$sourceFormat}\" is not available. See `ci-report-converter convert:map`");
    }
}
