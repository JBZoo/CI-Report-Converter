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

use JBZoo\CiReportConverter\Formats\GithubActions\GithubActions;
use JBZoo\CiReportConverter\Formats\GithubActions\GithubCase;
use JBZoo\CiReportConverter\Formats\GithubActions\GithubSuite;
use JBZoo\CiReportConverter\Formats\Source\SourceCase;
use JBZoo\CiReportConverter\Formats\Source\SourceSuite;

/**
 * Class GithubCliConverter
 * @package JBZoo\CiReportConverter\Converters
 */
class GithubCliConverter extends AbstractConverter
{
    public const TYPE = 'github-cli';
    public const NAME = 'GitHub Actions - CLI';

    /**
     * @inheritDoc
     */
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
     * @param SourceSuite               $sourceSuite
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
     * @param SourceCase                $sourceCase
     * @param GithubActions|GithubSuite $ghActions
     */
    private function renderTestCase(SourceCase $sourceCase, $ghActions): void
    {
        if (null !== $sourceCase->stdOut) {
            $level = GithubCase::LEVEL_ERROR;
        } elseif (null !== $sourceCase->errOut) {
            $level = GithubCase::LEVEL_ERROR;
        } elseif (null !== $sourceCase->failure) {
            $level = GithubCase::LEVEL_ERROR;
        } elseif (null !== $sourceCase->error) {
            $level = GithubCase::LEVEL_ERROR;
        } elseif (null !== $sourceCase->warning) {
            $level = GithubCase::LEVEL_WARNING;
        } elseif (null !== $sourceCase->skipped) {
            $level = GithubCase::LEVEL_DEBUG;
        } else {
            $level = GithubCase::LEVEL_ERROR;
        }

        $message = $sourceCase->getMessage();
        if ($message) {
            $case = $ghActions->addCase($this->cleanFilepath($sourceCase->file ?: ''));
            $case->line = $sourceCase->line;
            $case->column = $sourceCase->column;
            $case->level = $level;
            $case->message = $this->cleanFilepath($message);
        }
    }
}
