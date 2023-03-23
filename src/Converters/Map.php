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

final class Map
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

    public static function getAvailableFormats(?string $direction = null): array
    {
        $drivers = \array_keys(self::MAP_TESTS);
        \sort($drivers);

        if ($direction !== null) {
            return \array_filter(
                // @phpstan-ignore-next-line
                \array_map(static function (string $converterClass) use ($direction): ?string {
                    if (self::MAP_TESTS[$converterClass][$direction]) {
                        // @phpstan-ignore-next-line
                        return $converterClass::TYPE;
                    }

                    return null;
                }, $drivers),
            );
        }

        return \array_map(static fn (string $converterClass): string => $converterClass::TYPE, $drivers);
    }

    public static function getAvailableMetrics(): array
    {
        $result = [];

        foreach (self::MAP_METRICS as $driver) {
            $result[] = $driver::TYPE;
        }

        \sort($result);

        return $result;
    }

    public static function isAvailable(string $source, string $target): bool
    {
        return self::MAP_TESTS[$source][self::INPUT] && self::MAP_TESTS[$target][self::OUTPUT];
    }

    public static function getMarkdownTable(): string
    {
        $tableData = self::getTable();
        $header    = \array_keys($tableData);

        $rows = [];

        foreach ($tableData as $key => $info) {
            $rows[$key] = \array_values(\array_map(static fn (bool $value) => $value ? 'Yes' : '-', $info));

            \array_unshift($rows[$key], $key);
        }

        \array_unshift($header, 'Source/Target');

        return (new Table())
            ->setHeaders($header)
            ->appendRows($rows)
            ->render();
    }

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
            'See `ci-report-converter convert:map`',
        );
    }

    public static function getMetric(string $sourceFormat, int $flowId = 0): AbstractStatsTcConverter
    {
        foreach (self::MAP_METRICS as $class) {
            if ($class::TYPE === $sourceFormat) {
                return new $class([], $flowId);
            }
        }

        throw new Exception("The format \"{$sourceFormat}\" is not available. See `ci-report-converter convert:map`");
    }
}
