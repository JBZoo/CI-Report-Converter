<?php

/**
 * JBZoo Toolbox - Toolbox-CI
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Toolbox-CI
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Toolbox-CI
 */

declare(strict_types=1);

namespace JBZoo\ToolboxCI\Converters;

use JBZoo\ToolboxCI\Formats\GithubActions\GithubActions;
use JBZoo\ToolboxCI\Formats\GithubActions\GithubCase;
use JBZoo\ToolboxCI\Formats\GithubActions\GithubSuite;
use JBZoo\ToolboxCI\Formats\Source\SourceCase;
use JBZoo\ToolboxCI\Formats\Source\SourceSuite;

/**
 * Class GithubCliConverter
 * @package JBZoo\ToolboxCI\Converters
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
            $message = $sourceCase->stdOut;
        } elseif (null !== $sourceCase->errOut) {
            $level = GithubCase::LEVEL_ERROR;
            $message = $sourceCase->errOut;
        } elseif (null !== $sourceCase->failure) {
            $level = GithubCase::LEVEL_ERROR;
            $message = implode("\n", [$sourceCase->failure->message ?? '', $sourceCase->failure->details ?? '']);
        } elseif (null !== $sourceCase->error) {
            $level = GithubCase::LEVEL_ERROR;
            $message = implode("\n", [$sourceCase->error->message ?? '', $sourceCase->error->details ?? '']);
        } elseif (null !== $sourceCase->warning) {
            $level = GithubCase::LEVEL_WARNING;
            $message = implode("\n", [$sourceCase->warning->message ?? '', $sourceCase->warning->details ?? '']);
        } elseif (null !== $sourceCase->skipped) {
            $level = GithubCase::LEVEL_DEBUG;
            $message = implode("\n", [$sourceCase->skipped->message ?? '', $sourceCase->skipped->details ?? '']);
            $message = trim($message) ?: 'Skipped';
        } else {
            $level = GithubCase::LEVEL_ERROR;
            $message = '';
        }

        $message = trim($message ?: '');
        if ($message) {
            $case = $ghActions->addCase($this->cleanFilepath($sourceCase->file ?: ''));
            $case->line = $sourceCase->line;
            $case->column = $sourceCase->column;
            $case->level = $level;
            $case->message = $this->cleanFilepath($message);
        }
    }
}
