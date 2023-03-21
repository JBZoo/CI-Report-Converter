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

use JBZoo\CIReportConverter\Converters\CheckStyleConverter;
use JBZoo\CIReportConverter\Converters\Map;
use JBZoo\CIReportConverter\Converters\TeamCityTestsConverter;
use JBZoo\Cli\Codes;
use JBZoo\Cli\OutLvl;
use Symfony\Component\Console\Input\InputOption;

final class Convert extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->setName('convert')
            ->setDescription('Convert one report format to another one')
            ->addOption(
                'input-format',
                'S',
                InputOption::VALUE_REQUIRED,
                'Source format. Available options: <info>' . \implode(
                    ', ',
                    Map::getAvailableFormats(Map::INPUT),
                ) . '</info>',
                CheckStyleConverter::TYPE,
            )
            ->addOption(
                'input-file',
                'I',
                InputOption::VALUE_OPTIONAL,
                'File path with the original report format. If not set or empty, then the STDIN is used.',
            )
            ->addOption(
                'output-format',
                'T',
                InputOption::VALUE_REQUIRED,
                'Target format. Available options: <info>' . \implode(
                    ', ',
                    Map::getAvailableFormats(Map::OUTPUT),
                ) . '</info>',
                TeamCityTestsConverter::TYPE,
            )
            ->addOption(
                'output-file',
                'O',
                InputOption::VALUE_OPTIONAL,
                'File path with the result report format. If not set or empty, then the STDOUT is used.',
            )
            ->addOption(
                'root-path',
                'R',
                InputOption::VALUE_OPTIONAL,
                'If option is set, all absolute file paths will be converted to relative once.',
                '.',
            )
            ->addOption(
                'suite-name',
                'N',
                InputOption::VALUE_REQUIRED,
                "Set custom name of root group/suite (if it's possible).",
            )
            ->addOption(
                'tc-flow-id',
                'F',
                InputOption::VALUE_OPTIONAL,
                'Custom flowId in TeamCity output. Default value is PID of the tool.',
            )
            ->addOption(
                'non-zero-code',
                'Q',
                InputOption::VALUE_OPTIONAL,
                'Will exit with the code=1, if any violations are found.',
                'no',
            );

        parent::configure();
    }

    protected function executeAction(): int
    {
        $sourceReport = $this->getSourceCode();
        $rootPath = $this->getOptString('root-path');
        $suiteName = $this->getOptString('suite-name');
        $nonZeroCode = $this->getOptBool('non-zero-code');

        $casesAreFound = false;

        if ($sourceReport !== null) {
            $internalReport = Map::getConverter($this->getFormat('input-format'), Map::INPUT)
                ->setRootPath($rootPath)
                ->setRootSuiteName($suiteName)
                ->toInternal($sourceReport);

            $errorsCount = $internalReport->getErrorsCount();
            $warningCount = $internalReport->getWarningCount();
            $failureCount = $internalReport->getFailureCount();

            $casesAreFound = $errorsCount > 0 || $warningCount > 0 || $failureCount > 0;

            $targetReport = Map::getConverter($this->getFormat('output-format'), Map::OUTPUT)
                ->setRootPath($rootPath)
                ->setRootSuiteName($suiteName)
                ->setFlowId($this->getOptInt('tc-flow-id'))
                ->fromInternal($internalReport);

            if ($this->saveResult($targetReport)) {
                if ($errorsCount > 0) {
                    $this->_("Found errors: {$errorsCount}", OutLvl::E);
                }

                if ($warningCount > 0) {
                    $this->_("Found warnings: {$warningCount}", OutLvl::E);
                }

                if ($failureCount > 0) {
                    $this->_("Found failures: {$failureCount}", OutLvl::E);
                }
            }
        }

        return $nonZeroCode && $casesAreFound ? Codes::GENERAL_ERROR : Codes::OK;
    }

    private function getFormat(string $optionName): string
    {
        $format = \strtolower($this->getOptString($optionName));

        $validFormats = Map::getAvailableFormats();

        if (!\in_array($format, $validFormats, true)) {
            throw new Exception(
                "Format \"{$format}\" not found. See the option \"--{$optionName}\".\n" .
                'Available options: ' . \implode(',', $validFormats),
            );
        }

        return $format;
    }
}
