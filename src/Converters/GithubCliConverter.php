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

use JBZoo\CIReportConverter\Formats\GithubActions\GithubActions;
use JBZoo\CIReportConverter\Formats\GithubActions\GithubCase;
use JBZoo\CIReportConverter\Formats\GithubActions\GithubSuite;
use JBZoo\CIReportConverter\Formats\Source\SourceCase;
use JBZoo\CIReportConverter\Formats\Source\SourceSuite;

class GithubCliConverter extends AbstractConverter
{
    public const TYPE = 'github-cli';
    public const NAME = 'GitHub Actions - CLI';

    public function fromInternal(SourceSuite $sourceSuite): string
    {
        $ghActions = new GithubActions();

        if ($this->rootSuiteName) {
            $this->renderSuite($sourceSuite, $ghActions->addSuite($this->rootSuiteName));
        } else {
            $this->renderSuite($sourceSuite, $ghActions);
        }

        return (string)$ghActions;
    }

    /**
     * @param GithubActions|GithubSuite $ghActions
     */
    private function renderSuite(SourceSuite $sourceSuite, $ghActions): void
    {
        foreach ($sourceSuite->getCases() as $sourceCase) {
            $this->renderTestCase($sourceCase, $ghActions);
        }

        foreach ($sourceSuite->getSuites() as $sourceSuiteItem) {
            $this->renderSuite($sourceSuiteItem, $ghActions);
        }
    }

    /**
     * @param GithubActions|GithubSuite $ghActions
     */
    private function renderTestCase(SourceCase $sourceCase, $ghActions): void
    {
        if ($sourceCase->stdOut !== null) {
            $level = GithubCase::LEVEL_ERROR;
        } elseif ($sourceCase->errOut !== null) {
            $level = GithubCase::LEVEL_ERROR;
        } elseif ($sourceCase->failure !== null) {
            $level = GithubCase::LEVEL_ERROR;
        } elseif ($sourceCase->error !== null) {
            $level = GithubCase::LEVEL_ERROR;
        } elseif ($sourceCase->warning !== null) {
            $level = GithubCase::LEVEL_WARNING;
        } elseif ($sourceCase->skipped !== null) {
            $level = GithubCase::LEVEL_DEBUG;
        } else {
            $level = GithubCase::LEVEL_ERROR;
        }

        $message = $sourceCase->getMessage();
        if ($message) {
            $case          = $ghActions->addCase($this->cleanFilepath($sourceCase->file ?: ''));
            $case->line    = $sourceCase->line;
            $case->column  = $sourceCase->column;
            $case->level   = $level;
            $case->message = $this->cleanFilepath($message);
        }
    }
}
