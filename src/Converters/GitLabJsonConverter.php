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

use JBZoo\CiReportConverter\Formats\GitLabJson\GitLabJson;
use JBZoo\CiReportConverter\Formats\GitLabJson\GitLabJsonCase;
use JBZoo\CiReportConverter\Formats\Source\SourceCase;
use JBZoo\CiReportConverter\Formats\Source\SourceSuite;

/**
 * Class GitLabJsonConverter
 * @package JBZoo\CiReportConverter\Converters
 * @see     https://docs.gitlab.com/ee/user/project/merge_requests/code_quality.html#implementing-a-custom-tool
 */
class GitLabJsonConverter extends AbstractConverter
{
    public const TYPE = 'gitlab-json';
    public const NAME = 'GitLab - JSON';

    /**
     * @inheritDoc
     */
    public function fromInternal(SourceSuite $sourceSuite): string
    {
        $gitLabJson = new GitLabJson();
        $this->renderSuite($sourceSuite, $gitLabJson);

        return (string)$gitLabJson;
    }

    /**
     * @param SourceSuite $sourceSuite
     * @param GitLabJson  $gitLabJson
     */
    private function renderSuite(SourceSuite $sourceSuite, GitLabJson $gitLabJson): void
    {
        foreach ($sourceSuite->getCases() as $sourceCase) {
            $this->renderTestCase($sourceCase, $gitLabJson);
        }

        foreach ($sourceSuite->getSuites() as $sourceSuiteItem) {
            $this->renderSuite($sourceSuiteItem, $gitLabJson);
        }
    }

    /**
     * @param SourceCase $sourceCase
     * @param GitLabJson $gitLabJson
     */
    private function renderTestCase(SourceCase $sourceCase, GitLabJson $gitLabJson): void
    {
        if (null !== $sourceCase->stdOut) {
            $severity = GitLabJsonCase::SEVERITY_INFO;
        } elseif (null !== $sourceCase->errOut) {
            $severity = GitLabJsonCase::SEVERITY_MAJOR;
        } elseif (null !== $sourceCase->failure) {
            $severity = GitLabJsonCase::SEVERITY_BLOCKER;
        } elseif (null !== $sourceCase->error) {
            $severity = GitLabJsonCase::SEVERITY_BLOCKER;
        } elseif (null !== $sourceCase->warning) {
            $severity = GitLabJsonCase::SEVERITY_MAJOR;
        } elseif (null !== $sourceCase->skipped) {
            $severity = GitLabJsonCase::SEVERITY_INFO;
        } else {
            $severity = GitLabJsonCase::SEVERITY_MAJOR;
        }

        $description = $sourceCase->getMessage();
        if ($description) {
            $case = $gitLabJson->addCase();
            $case->description = $this->cleanFilepath($description);
            $case->severity = $severity;
            $case->name = $this->cleanFilepath($sourceCase->file ?: '');
            $case->line = $sourceCase->line;
        }
    }
}
