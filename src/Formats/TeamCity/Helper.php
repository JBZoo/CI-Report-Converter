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

namespace JBZoo\CIReportConverter\Formats\TeamCity;

class Helper
{
    public const PREDEFINED_METRICS = [
        'ArtifactsSize',
        'VisibleArtifactsSize',
        'buildStageDuration:artifactsPublishing',
        'buildStageDuration:buildStepRunner_<N>',
        'buildStageDuration:sourcesUpdate',
        'buildStageDuration:dependenciesResolving',
        'BuildDuration',
        'BuildDurationNetTime',
        'CodeCoverageB',
        'CodeCoverageC',
        'CodeCoverageL',
        'CodeCoverageM',
        'CodeCoverageR',
        'CodeCoverageS',
        'CodeCoverageAbsBCovered',
        'CodeCoverageAbsBTotal',
        'CodeCoverageAbsCCovered',
        'CodeCoverageAbsCTotal',
        'CodeCoverageAbsLCovered',
        'CodeCoverageAbsLTotal',
        'CodeCoverageAbsMCovered',
        'CodeCoverageAbsMTotal',
        'CodeCoverageAbsRCovered',
        'CodeCoverageAbsRTotal',
        'CodeCoverageAbsSCovered',
        'CodeCoverageAbsSTotal',
        'DuplicatorStats',
        'TotalTestCount',
        'PassedTestCount',
        'FailedTestCount',
        'IgnoredTestCount',
        'InspectionStatsE',
        'InspectionStatsW',
        'SuccessRate',
        'TimeSpentInQueue',
    ];
    private const TIMESTAMP_FORMAT = 'Y-m-d\TH:i:s.uO';

    public static function printEvent(string $eventName, array $params = []): string
    {
        self::ensureValidJavaId($eventName);

        $result = "\n##teamcity[{$eventName}";

        foreach ($params as $propertyName => $propertyValue) {
            $escapedValue = self::escapeValue((string)$propertyValue);
            if (\is_int($propertyName)) {
                $result .= " '{$escapedValue}'"; // Value without name; skip the key and dump just the value
            } else {
                self::ensureValidJavaId($propertyName);
                $result .= " {$propertyName}='{$escapedValue}'"; // Classic name=value pair
            }
        }

        $result .= "]\n";

        return $result;
    }

    /**
     * Checks if given value is valid Java ID.
     * Valid Java ID starts with alpha-character and continues with mix of alphanumeric characters and `-`.
     */
    public static function ensureValidJavaId(string $value): void
    {
        if (!\preg_match('/^[a-z][-a-z0-9]+$/i', $value)) {
            throw new Exception("Value \"{$value}\" is not valid Java ID.");
        }
    }

    /**
     * @param null|\DateTime $datetime either date with timestamp or `NULL` for now
     */
    public static function formatTimestamp(?\DateTime $datetime = null): string
    {
        $datetime ??= new \DateTime();
        $formatted = $datetime->format(self::TIMESTAMP_FORMAT);

        // We need to pass only 3 microsecond digits.
        // 2000-01-01T12:34:56.123450+0100 <- before
        // 2000-01-01T12:34:56.123+0100 <- after
        return \substr($formatted, 0, 23) . \substr($formatted, 26);
    }

    private static function escapeValue(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $escapeCharacterMap = [
            '\'' => '|\'',
            "\n" => '|n',
            "\r" => '|r',
            '|'  => '||',
            '['  => '|[',
            ']'  => '|]',
        ];

        return \preg_replace_callback(
            '/([\'\n\r|[\]])|\\\\u(\d{4})/',
            static function (array $matches) use ($escapeCharacterMap) {
                if ($matches[1]) {
                    return $escapeCharacterMap[$matches[1]];
                }

                if ($matches[2]) {
                    return '|0x' . $matches[2];
                }

                throw new Exception('Unexpected match combination.');
            },
            $value,
        );
    }
}
