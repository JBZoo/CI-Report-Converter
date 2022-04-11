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

use JBZoo\Cli\CliCommand;
use JBZoo\Cli\Helper;

/**
 * Class AbstractCommand
 * @package JBZoo\CiReportConverter\Commands
 * @psalm-suppress PropertyNotSetInConstructor
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
                $this->_("File \"{$filename}\" not found", Helper::VERB_ERROR);
                return '';
            }

            return (string)\file_get_contents($filename);
        }

        if (0 === \ftell(\STDIN)) {
            $contents = '';

            while (!\feof(\STDIN)) {
                $contents .= \fread(\STDIN, 1024);
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
        if ($filename = $this->getOptString('output-file')) {
            \file_put_contents($filename, $result);
            $this->_("Result is saved: {$filename}");
        } else {
            $this->_($result);
        }
    }
}
