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

use JBZoo\CIReportConverter\Formats\GitLabJson\GitLabJson;
use JBZoo\CIReportConverter\Formats\GitLabJson\GitLabJsonCase;
use JBZoo\CIReportConverter\Formats\Source\SourceCase;
use JBZoo\CIReportConverter\Formats\Source\SourceSuite;

/**
 * @see     https://docs.gitlab.com/ee/user/project/merge_requests/code_quality.html#implementing-a-custom-tool
 */
class GitLabJsonConverter extends AbstractConverter
{
    public const TYPE = 'gitlab-json';
    public const NAME = 'GitLab - JSON';

    public function fromInternal(SourceSuite $sourceSuite): string
    {
        $gitLabJson = new GitLabJson();
        $this->renderSuite($sourceSuite, $gitLabJson);

        return (string)$gitLabJson . \PHP_EOL;
    }

    private function renderSuite(SourceSuite $sourceSuite, GitLabJson $gitLabJson): void
    {
        foreach ($sourceSuite->getCases() as $sourceCase) {
            $this->renderTestCase($sourceCase, $gitLabJson);
        }

        foreach ($sourceSuite->getSuites() as $sourceSuiteItem) {
            $this->renderSuite($sourceSuiteItem, $gitLabJson);
        }
    }

    private function renderTestCase(SourceCase $sourceCase, GitLabJson $gitLabJson): void
    {
        if ($sourceCase->stdOut !== null) {
            $severity = GitLabJsonCase::SEVERITY_INFO;
        } elseif ($sourceCase->errOut !== null) {
            $severity = GitLabJsonCase::SEVERITY_MAJOR;
        } elseif ($sourceCase->failure !== null) {
            $severity = GitLabJsonCase::SEVERITY_BLOCKER;
        } elseif ($sourceCase->error !== null) {
            $severity = GitLabJsonCase::SEVERITY_BLOCKER;
        } elseif ($sourceCase->warning !== null) {
            $severity = GitLabJsonCase::SEVERITY_MAJOR;
        } elseif ($sourceCase->skipped !== null) {
            $severity = GitLabJsonCase::SEVERITY_INFO;
        } else {
            $severity = GitLabJsonCase::SEVERITY_MAJOR;
        }

        $description = $sourceCase->getMessage();
        if ($description) {
            $case              = $gitLabJson->addCase();
            $case->description = $this->cleanFilepath($description);
            $case->severity    = $severity;
            $case->name        = $this->cleanFilepath($sourceCase->file ?: '');
            $case->line        = $sourceCase->line;
        }
    }
}
