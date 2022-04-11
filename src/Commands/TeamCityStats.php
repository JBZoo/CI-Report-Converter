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

namespace JBZoo\CiReportConverter\Commands;

use JBZoo\CiReportConverter\Converters\Map;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class TeamCityStats
 * @package JBZoo\CiReportConverter\Commands
 */
class TeamCityStats extends AbstractCommand
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $req = InputOption::VALUE_REQUIRED;
        $opt = InputOption::VALUE_OPTIONAL;

        $formats = 'Available options: <comment>' . \implode(', ', Map::getAvailableMetrics()) . '</comment>';

        $this
            ->setName('teamcity:stats')
            ->setDescription('Push code metrics to TeamCity Stats')
            ->addOption('input-format', 'S', $req, "Source format. {$formats}")
            ->addOption('input-file', 'I', $opt, "File path with the original report format. " .
                "If not set or empty, then the STDIN is used.")
            ->addOption('output-file', 'O', $opt, "File path with the result report format. " .
                "If not set or empty, then the STDOUT is used.")
            ->addOption('root-path', 'R', $opt, 'If option is set, ' .
                'all absolute file paths will be converted to relative once.', '.')
            ->addOption('tc-flow-id', 'F', $opt, 'Custom flowId in TeamCity output. Default value is PID of the tool.');

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function executeAction(): int
    {
        $inputFormat = $this->getFormat();

        $output = self::convertMetric($this->getSourceCode(), $inputFormat, $this->getOptInt('tc-flow-id'));

        $this->saveResult($output);

        return 0;
    }

    /**
     * @return string
     */
    private function getFormat(): string
    {
        $format = \strtolower($this->getOptString('input-format'));

        $validFormats = Map::getAvailableMetrics();

        if (!\in_array($format, $validFormats, true)) {
            throw new Exception(
                "Format \"{$format}\" not found. See the option \"--input-format\".\n" .
                "Available options: " . \implode(',', $validFormats)
            );
        }

        return $format;
    }

    /**
     * @param string   $sourceCode
     * @param string   $sourceFormat
     * @param int|null $flowId
     * @return string
     */
    private static function convertMetric(string $sourceCode, string $sourceFormat, ?int $flowId = null): string
    {
        $sourceCode = \trim($sourceCode);
        if ('' === $sourceCode) {
            return '';
        }

        $tcStatsConverter = Map::getMetric($sourceFormat, $flowId);

        return $tcStatsConverter->fromInternalMetric($tcStatsConverter->toInternalMetric($sourceCode));
    }
}
