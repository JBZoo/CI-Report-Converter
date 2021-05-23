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

use JBZoo\CiReportConverter\Converters\CheckStyleConverter;
use JBZoo\CiReportConverter\Converters\Map;
use JBZoo\CiReportConverter\Converters\TeamCityTestsConverter;
use Symfony\Component\Console\Input\InputOption;

use function JBZoo\Utils\bool;

/**
 * Class Convert
 * @package JBZoo\CiReportConverter\Commands
 */
class Convert extends AbstractCommand
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $req = InputOption::VALUE_REQUIRED;
        $opt = InputOption::VALUE_OPTIONAL;

        $this
            ->setName('convert')
            ->setDescription('Convert one report format to another one')
            ->addOption('input-format', 'S', $req, 'Source format. Available options: <info>'
                . implode(', ', Map::getAvailableFormats(Map::INPUT)) . '</info>', CheckStyleConverter::TYPE)
            ->addOption('input-file', 'I', $opt, "File path with the original report format. " .
                "If not set or empty, then the STDIN is used.")
            ->addOption('output-format', 'T', $req, 'Target format. Available options: <info>'
                . implode(', ', Map::getAvailableFormats(Map::OUTPUT)) . '</info>', TeamCityTestsConverter::TYPE)
            ->addOption('output-file', 'O', $opt, "File path with the result report format. " .
                "If not set or empty, then the STDOUT is used.")
            ->addOption('root-path', 'R', $opt, 'If option is set, ' .
                'all absolute file paths will be converted to relative once.', '.')
            ->addOption('suite-name', 'N', $req, "Set custom name of root group/suite (if it's possible).")
            ->addOption('tc-flow-id', 'F', $opt, 'Custom flowId in TeamCity output. Default value is PID of the tool.')
            ->addOption('non-zero-code', 'Q', $opt, 'Will exit with the code=1, if any violations are found.', 'no');
    }

    /**
     * @inheritDoc
     */
    protected function executeAction(): int
    {
        $sourceReport = $this->getSourceCode();
        $rootPath = (string)$this->getOption('root-path') ?: null;
        $suiteName = (string)$this->getOption('suite-name') ?: null;
        $nonZeroCode = bool($this->getOption('non-zero-code'));

        $casesAreFound = false;

        if ($sourceReport) {
            $internalReport = Map::getConverter($this->getFormat('input-format'), Map::INPUT)
                ->setRootPath($rootPath)
                ->setRootSuiteName($suiteName)
                ->toInternal($sourceReport);

            $casesAreFound =
                $internalReport->getErrorsCount() > 0 ||
                $internalReport->getWarningCount() > 0 ||
                $internalReport->getFailureCount() > 0;

            $targetReport = Map::getConverter($this->getFormat('output-format'), Map::OUTPUT)
                ->setRootPath($rootPath)
                ->setRootSuiteName($suiteName)
                ->setFlowId((int)$this->getOption('tc-flow-id'))
                ->fromInternal($internalReport);

            $this->saveResult($targetReport);
        }

        return $nonZeroCode && $casesAreFound ? 1 : 0;
    }

    /**
     * @param string $optionName
     * @return string
     */
    private function getFormat(string $optionName): string
    {
        $format = strtolower(trim((string)$this->getOption($optionName)));

        $validFormats = Map::getAvailableFormats();

        if (!in_array($format, $validFormats, true)) {
            throw new Exception(
                "Format \"{$format}\" not found. See the option \"--{$optionName}\".\n" .
                "Available options: " . implode(',', $validFormats)
            );
        }

        return $format;
    }
}
