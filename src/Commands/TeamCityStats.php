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

use JBZoo\CiReportConverter\Converters\Factory;
use JBZoo\CiReportConverter\Converters\Map;
use Symfony\Component\Console\Input\InputOption;

use function JBZoo\Utils\int;

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
        $formats = 'Available options: <comment>' . implode(', ', Map::getAvailableMetrics()) . '</comment>';

        $this
            ->setName('teamcity:stats')
            ->setDescription('Push code metrics to TeamCity Stats')
            ->addOption('input-format', 'S', InputOption::VALUE_REQUIRED, "Source format. {$formats}")
            ->addOption('input-file', 'I', InputOption::VALUE_OPTIONAL, "File path with the original report format. " .
                "If not set or empty, then the STDIN is used.")
            ->addOption('output-file', 'O', InputOption::VALUE_OPTIONAL, "File path with the result report format. " .
                "If not set or empty, then the STDOUT is used.")
            ->addOption('root-path', 'R', InputOption::VALUE_OPTIONAL, 'If option is set, ' .
                'all absolute file paths will be converted to relative once.', '.')
            ->addOption('tc-flow-id', 'F', InputOption::VALUE_OPTIONAL, 'Custom flowId for TeamCity output');
    }

    /**
     * @inheritDoc
     */
    protected function executeAction(): int
    {
        $inputFormat = $this->getFormat();

        $output = Factory::convertMetric($this->getSourceCode(), $inputFormat, int($this->getOption('tc-flow-id')));

        $this->saveResult($output);

        return 0;
    }

    /**
     * @return string
     */
    private function getFormat(): string
    {
        $format = strtolower(trim((string)$this->getOption('input-format')));

        $validFormats = Map::getAvailableMetrics();

        if (!in_array($format, $validFormats, true)) {
            throw new Exception(
                "Format \"{$format}\" not found. See the option \"--input-format\".\n" .
                "Available options: " . implode(',', $validFormats)
            );
        }

        return $format;
    }
}
