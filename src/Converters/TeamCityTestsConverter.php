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

use JBZoo\CIReportConverter\Formats\Source\SourceCase;
use JBZoo\CIReportConverter\Formats\Source\SourceSuite;
use JBZoo\CIReportConverter\Formats\TeamCity\TeamCity;
use JBZoo\CIReportConverter\Formats\TeamCity\Writers\AbstractWriter;
use JBZoo\CIReportConverter\Formats\TeamCity\Writers\Buffer;

final class TeamCityTestsConverter extends AbstractConverter
{
    public const TYPE = 'tc-tests';
    public const NAME = 'TeamCity - Tests';

    private TeamCity $tcLogger;

    public function __construct(array $params = [], int $flowId = 0, ?AbstractWriter $tcWriter = null)
    {
        $this->tcLogger = new TeamCity($tcWriter ?? new Buffer(), $flowId, $params);
    }

    public function fromInternal(SourceSuite $sourceSuite): string
    {
        if ($this->flowId > 0) {
            $this->tcLogger->setFlowId($this->flowId);
        }

        $testCount = $sourceSuite->getCasesCount();
        if ($testCount > 0) {
            $this->tcLogger->write('testCount', ['count' => $testCount]);
        }

        $this->renderSuite($sourceSuite);

        $buffer = $this->tcLogger->getWriter();
        if ($buffer instanceof Buffer) {
            return \implode('', $buffer->getBuffer());
        }

        return '';
    }

    private function renderSuite(SourceSuite $sourceSuite): void
    {
        $params = [];
        if ($sourceSuite->file !== null && $sourceSuite->file !== '') {
            $params = ['locationHint' => "php_qn://{$sourceSuite->file}::\\{$sourceSuite->name}"];
        }

        if ($sourceSuite->name !== '') {
            $this->tcLogger->testSuiteStarted($sourceSuite->name, $params);
        }

        foreach ($sourceSuite->getCases() as $case) {
            $this->renderTestCase($case);
        }

        foreach ($sourceSuite->getSuites() as $suite) {
            $this->renderSuite($suite);
        }

        if ($sourceSuite->name !== '') {
            $this->tcLogger->testSuiteFinished($sourceSuite->name);
        }
    }

    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function renderTestCase(SourceCase $case): void
    {
        $logger = $this->tcLogger;

        $params = [];
        if ($case->file !== null && $case->class !== null) {
            $params = ['locationHint' => "php_qn://{$case->file}::\\{$case->class}::{$case->name}"];
        } elseif ($case->file !== null) {
            $params = ['locationHint' => "php_qn://{$case->file}"];
        }

        $logger->testStarted($case->name, $params);

        if ($case->skipped !== null) {
            $logger->testSkipped($case->name, $case->skipped->message, $case->skipped->details, $case->time);
        } else {
            $failureObject = $case->failure ?? $case->error ?? $case->warning;
            if ($failureObject !== null) {
                $params = [
                    'message'  => $failureObject->message,
                    'details'  => $failureObject->details,
                    'duration' => $case->time,
                ];

                $messageData        = $failureObject->parseDescription();
                $params['actual']   = $messageData->get('actual');
                $params['expected'] = $messageData->get('expected');
                $params['details']  = $messageData->get('description') ?? $params['details'];
                $params['message']  = $messageData->get('message') ?? $params['message'];
                $logger->testFailed($case->name, $params);
            }
        }

        if ($case->stdOut !== null) {
            $logger->getWriter()->write($case->stdOut);
        }

        if ($case->errOut !== null) {
            $logger->getWriter()->write($case->errOut);
        }

        $logger->testFinished($case->name, $case->time);
    }
}
