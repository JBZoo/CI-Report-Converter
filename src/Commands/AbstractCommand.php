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

use JBZoo\Cli\Cli;
use JBZoo\Cli\CliCommand;

/**
 * Class AbstractCommand
 * @package JBZoo\CiReportConverter\Commands
 */
abstract class AbstractCommand extends CliCommand
{
    /**
     * @return string
     */
    protected function getSourceCode(): string
    {
        if ($filename = $this->getOptString('input-file')) {
            if (!\realpath($filename) && !\file_exists($filename)) {
                $this->_("File \"{$filename}\" not found", Cli::ERROR);
                return '';
            }

            return (string)\file_get_contents($filename);
        }

        $contents = (string)self::getStdIn();
        if (\trim($contents) === '') {
            throw new Exception("Please provide input-file or use STDIN as input (CLI pipeline).");
        }

        return $contents;
    }

    /**
     * @param string $result
     * @return bool
     */
    protected function saveResult(string $result): bool
    {
        if ($filename = $this->getOptString('output-file')) {
            \file_put_contents($filename, $result);
            $this->_("Result is saved: {$filename}");
            return true;
        }

        $this->_($result);
        return false;
    }
}
