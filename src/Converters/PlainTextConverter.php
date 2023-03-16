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

use JBZoo\CIReportConverter\Formats\PlainText\PlainText;
use JBZoo\CIReportConverter\Formats\PlainText\PlainTextCase;
use JBZoo\CIReportConverter\Formats\PlainText\PlainTextSuite;
use JBZoo\CIReportConverter\Formats\Source\SourceCase;
use JBZoo\CIReportConverter\Formats\Source\SourceSuite;

class PlainTextConverter extends AbstractConverter
{
    public const TYPE = 'plain';
    public const NAME = 'Plain Text';

    public function fromInternal(SourceSuite $sourceSuite): string
    {
        $plainTextSuite = new PlainText();

        if ($this->rootSuiteName) {
            $this->renderSuite($sourceSuite, $plainTextSuite->addSuite($this->rootSuiteName));
        } else {
            $this->renderSuite($sourceSuite, $plainTextSuite);
        }

        return (string)$plainTextSuite;
    }

    /**
     * @param PlainText|PlainTextSuite $plainTextSuite
     */
    private function renderSuite(SourceSuite $sourceSuite, $plainTextSuite): void
    {
        foreach ($sourceSuite->getCases() as $sourceCase) {
            $this->renderTestCase($sourceCase, $plainTextSuite);
        }

        foreach ($sourceSuite->getSuites() as $sourceSuiteItem) {
            $this->renderSuite($sourceSuiteItem, $plainTextSuite);
        }
    }

    /**
     * @param PlainText|PlainTextSuite $plainTextSuite
     */
    private function renderTestCase(SourceCase $sourceCase, $plainTextSuite): void
    {
        if ($sourceCase->stdOut !== null) {
            $level = PlainTextCase::LEVEL_ERROR;
        } elseif ($sourceCase->errOut !== null) {
            $level = PlainTextCase::LEVEL_ERROR;
        } elseif ($sourceCase->failure !== null) {
            $level = PlainTextCase::LEVEL_ERROR;
        } elseif ($sourceCase->error !== null) {
            $level = PlainTextCase::LEVEL_ERROR;
        } elseif ($sourceCase->warning !== null) {
            $level = PlainTextCase::LEVEL_WARNING;
        } elseif ($sourceCase->skipped !== null) {
            $level = PlainTextCase::LEVEL_DEBUG;
        } else {
            $level = PlainTextCase::LEVEL_ERROR;
        }

        $message = $sourceCase->getMessage();
        if ($message) {
            $case          = $plainTextSuite->addCase($this->cleanFilepath($sourceCase->file ?: ''));
            $case->line    = $sourceCase->line;
            $case->column  = $sourceCase->column;
            $case->level   = $level;
            $case->message = $this->cleanFilepath($message);
        }
    }
}
