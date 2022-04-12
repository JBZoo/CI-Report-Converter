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

namespace JBZoo\CiReportConverter\Converters;

use JBZoo\CiReportConverter\Formats\PlainText\PlainText;
use JBZoo\CiReportConverter\Formats\PlainText\PlainTextCase;
use JBZoo\CiReportConverter\Formats\PlainText\PlainTextSuite;
use JBZoo\CiReportConverter\Formats\Source\SourceCase;
use JBZoo\CiReportConverter\Formats\Source\SourceSuite;

/**
 * Class PlainTextConverter
 * @package JBZoo\CiReportConverter\Converters
 */
class PlainTextConverter extends AbstractConverter
{
    public const TYPE = 'plain';
    public const NAME = 'Plain Text';

    /**
     * @inheritDoc
     */
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
     * @param SourceSuite              $sourceSuite
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
     * @param SourceCase               $sourceCase
     * @param PlainText|PlainTextSuite $plainTextSuite
     */
    private function renderTestCase(SourceCase $sourceCase, $plainTextSuite): void
    {
        if (null !== $sourceCase->stdOut) {
            $level = PlainTextCase::LEVEL_ERROR;
        } elseif (null !== $sourceCase->errOut) {
            $level = PlainTextCase::LEVEL_ERROR;
        } elseif (null !== $sourceCase->failure) {
            $level = PlainTextCase::LEVEL_ERROR;
        } elseif (null !== $sourceCase->error) {
            $level = PlainTextCase::LEVEL_ERROR;
        } elseif (null !== $sourceCase->warning) {
            $level = PlainTextCase::LEVEL_WARNING;
        } elseif (null !== $sourceCase->skipped) {
            $level = PlainTextCase::LEVEL_DEBUG;
        } else {
            $level = PlainTextCase::LEVEL_ERROR;
        }

        $message = $sourceCase->getMessage();
        if ($message) {
            $case = $plainTextSuite->addCase($this->cleanFilepath($sourceCase->file ?: ''));
            $case->line = $sourceCase->line;
            $case->column = $sourceCase->column;
            $case->level = $level;
            $case->message = $this->cleanFilepath($message);
        }
    }
}
