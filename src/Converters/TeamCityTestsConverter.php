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

use JBZoo\ToolboxCI\Formats\Source\SourceCase;
use JBZoo\ToolboxCI\Formats\Source\SourceSuite;
use JBZoo\ToolboxCI\Formats\TeamCity\TeamCity;
use JBZoo\ToolboxCI\Formats\TeamCity\Writers\AbstractWriter;
use JBZoo\ToolboxCI\Formats\TeamCity\Writers\Buffer;

/**
 * Class TeamCityTestsConverter
 * @package JBZoo\ToolboxCI\Converters
 */
class TeamCityTestsConverter extends AbstractConverter
{
    public const TYPE = 'tc-tests';
    public const NAME = 'TeamCity - Tests';

    /**
     * @var TeamCity
     */
    private $tcLogger;

    /**
     * TeamCityTestsConverter constructor.
     * @param array               $params
     * @param int|null            $flowId
     * @param AbstractWriter|null $tcWriter
     */
    public function __construct(array $params = [], ?int $flowId = null, ?AbstractWriter $tcWriter = null)
    {
        $this->tcLogger = new TeamCity($tcWriter ?: new Buffer(), $flowId, $params);
    }

    /**
     * @inheritDoc
     */
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
            return implode('', $buffer->getBuffer());
        }

        return '';
    }

    /**
     * @param SourceSuite $sourceSuite
     */
    private function renderSuite(SourceSuite $sourceSuite): void
    {
        $params = [];
        if ($sourceSuite->file) {
            $params = ['locationHint' => "php_qn://{$sourceSuite->file}::\\{$sourceSuite->name}"];
        }

        if ($sourceSuite->name) {
            $this->tcLogger->testSuiteStarted($sourceSuite->name, $params);
        }

        foreach ($sourceSuite->getCases() as $case) {
            $this->renderTestCase($case);
        }

        foreach ($sourceSuite->getSuites() as $suite) {
            $this->renderSuite($suite);
        }

        if ($sourceSuite->name) {
            $this->tcLogger->testSuiteFinished($sourceSuite->name);
        }
    }

    /**
     * @param SourceCase $case
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function renderTestCase(SourceCase $case): void
    {
        $logger = $this->tcLogger;

        $params = [];
        if ($case->file && $case->class) {
            $params = ['locationHint' => "php_qn://{$case->file}::\\{$case->class}::{$case->name}"];
        } elseif ($case->file) {
            $params = ['locationHint' => "php_qn://{$case->file}"];
        }

        $logger->testStarted($case->name, $params);

        if ($skippedOutput = $case->skipped) {
            $logger->testSkipped($case->name, $skippedOutput->message, $skippedOutput->details, $case->time);
        } else {
            $failureObject = $case->failure ?? $case->error ?? $case->warning;
            if ($failureObject) {
                $params = [
                    'message'  => $failureObject->message,
                    'details'  => $failureObject->details,
                    'duration' => $case->time,
                ];

                $messageData = $failureObject->parseDescription();
                $params['actual'] = $messageData->get('actual');
                $params['expected'] = $messageData->get('expected');
                $params['details'] = $messageData->get('description') ?? $params['details'];
                $params['message'] = $messageData->get('message') ?? $params['message'];
                $logger->testFailed($case->name, $params);
            }
        }

        if ($case->stdOut) {
            $logger->getWriter()->write($case->stdOut);
        }

        if ($case->errOut) {
            $logger->getWriter()->write($case->errOut);
        }

        $logger->testFinished($case->name, $case->time);
    }
}
