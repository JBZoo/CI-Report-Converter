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

namespace JBZoo\CIReportConverter\Commands;

use JBZoo\CIReportConverter\Converters\Map;
use JBZoo\Cli\Codes;
use Symfony\Component\Console\Input\InputOption;

final class TeamCityStats extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->setName('teamcity:stats')
            ->setDescription('Push code metrics to TeamCity Stats')
            ->addOption(
                'input-format',
                'S',
                InputOption::VALUE_REQUIRED,
                'Source format. Available options: <comment>' . \implode(
                    ', ',
                    Map::getAvailableMetrics(),
                ) . '</comment>',
            )
            ->addOption(
                'input-file',
                'I',
                InputOption::VALUE_OPTIONAL,
                'File path with the original report format. ' .
                'If not set or empty, then the STDIN is used.',
            )
            ->addOption(
                'output-file',
                'O',
                InputOption::VALUE_OPTIONAL,
                'File path with the result report format. ' .
                'If not set or empty, then the STDOUT is used.',
            )
            ->addOption(
                'root-path',
                'R',
                InputOption::VALUE_OPTIONAL,
                'If option is set, ' .
                'all absolute file paths will be converted to relative once.',
                '.',
            )
            ->addOption(
                'tc-flow-id',
                'F',
                InputOption::VALUE_OPTIONAL,
                'Custom flowId in TeamCity output. Default value is PID of the tool.',
            );

        parent::configure();
    }

    protected function executeAction(): int
    {
        $inputFormat = $this->getFormat();

        $output = self::convertMetric(
            $this->getSourceCode(),
            $inputFormat,
            $this->getOptInt('tc-flow-id'),
        );

        $this->saveResult($output);

        return Codes::OK;
    }

    private function getFormat(): string
    {
        $format = \strtolower($this->getOptString('input-format'));

        $validFormats = Map::getAvailableMetrics();

        if (!\in_array($format, $validFormats, true)) {
            throw new Exception(
                "Format \"{$format}\" not found. See help for the option \"--input-format\".\n" .
                'Available options: ' . \implode(',', $validFormats),
            );
        }

        return $format;
    }

    private static function convertMetric(?string $sourceCode, string $sourceFormat, ?int $flowId = null): string
    {
        if ($sourceCode === null) {
            return '';
        }

        $tcStatsConverter = Map::getMetric($sourceFormat, $flowId);

        return $tcStatsConverter->fromInternalMetric($tcStatsConverter->toInternalMetric($sourceCode));
    }
}
