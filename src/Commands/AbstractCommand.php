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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 * @package JBZoo\CiReportConverter\Commands
 */
abstract class AbstractCommand extends Command
{
    /**
     * @var InputInterface
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected $input;

    /**
     * @var OutputInterface
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected $output;

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this
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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        return $this->executeAction();
    }

    /**
     * @return int
     */
    abstract protected function executeAction(): int;

    /**
     * @return string
     */
    protected function getSourceCode(): string
    {
        if ($filename = (string)$this->getOption('input-file')) {
            if (!realpath($filename) && !file_exists($filename)) {
                $this->output->writeln("Warning: File \"{$filename}\" not found");
                return '';
            }

            return (string)file_get_contents($filename);
        }

        if (0 === ftell(STDIN)) {
            $contents = '';

            while (!feof(STDIN)) {
                $contents .= fread(STDIN, 1024);
            }

            return $contents;
        }

        throw new Exception("Please provide input-file or use STDIN as input (CLI pipeline).");
    }

    /**
     * @param string $result
     */
    protected function saveResult(string $result): void
    {
        if ($filename = (string)$this->getOption('output-file')) {
            file_put_contents($filename, $result);
            $this->output->writeln("Result is saved: {$filename}");
        } else {
            $this->output->write($result);
        }
    }

    /**
     * @param string $optionName
     * @return bool|string|null
     */
    protected function getOption(string $optionName)
    {
        $optionValue = $this->input->getOption($optionName);
        if (is_array($optionValue)) {
            return $optionValue[0];
        }

        return $optionValue;
    }
}
