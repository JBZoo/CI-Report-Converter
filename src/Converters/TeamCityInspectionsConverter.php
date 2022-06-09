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

use JBZoo\CiReportConverter\Formats\Source\SourceCase;
use JBZoo\CiReportConverter\Formats\Source\SourceSuite;
use JBZoo\CiReportConverter\Formats\TeamCity\TeamCity;
use JBZoo\CiReportConverter\Formats\TeamCity\Writers\AbstractWriter;
use JBZoo\CiReportConverter\Formats\TeamCity\Writers\Buffer;

/**
 * Class TeamCityInspectionsConverter
 * @package JBZoo\CiReportConverter\Converters
 */
class TeamCityInspectionsConverter extends AbstractConverter
{
    public const TYPE = 'tc-inspections';
    public const NAME = 'TeamCity - Inspections';

    /**
     * @var TeamCity
     */
    private TeamCity $tcLogger;

    /**
     * @var string
     */
    private string $globalPrefix = '';

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

        $this->globalPrefix = \trim($sourceSuite->name) ?: TeamCity::DEFAULT_INSPECTION_ID;
        $this->renderSuite($sourceSuite);

        $buffer = $this->tcLogger->getWriter();
        if ($buffer instanceof Buffer) {
            return \implode('', $buffer->getBuffer());
        }

        return '';
    }

    /**
     * @param SourceSuite $sourceSuite
     */
    private function renderSuite(SourceSuite $sourceSuite): void
    {
        foreach ($sourceSuite->getCases() as $case) {
            $this->renderTestCase($case, $sourceSuite->name);
        }

        foreach ($sourceSuite->getSuites() as $suite) {
            $this->renderSuite($suite);
        }
    }

    /**
     * @param SourceCase $case
     * @param string     $suiteName
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function renderTestCase(SourceCase $case, string $suiteName): void
    {
        $failureObject = null;
        $severity = null;

        if ($case->failure) {
            $severity = TeamCity::SEVERITY_ERROR;
            $failureObject = $case->failure;
        } elseif ($case->error) {
            $severity = TeamCity::SEVERITY_ERROR;
            $failureObject = $case->error;
        } elseif ($case->warning) {
            $severity = TeamCity::SEVERITY_WARNING;
            $failureObject = $case->warning;
        } elseif ($case->skipped) {
            $severity = TeamCity::SEVERITY_WARNING_WEAK;
            $failureObject = $case->skipped;
        }

        /** @phpstan-ignore-next-line */
        if (!$failureObject || !$severity) {
            return;
        }

        $messageData = $failureObject->parseDescription();
        $title = "{$suiteName} / {$case->name}";
        $message = $messageData->get('message') ?? $failureObject->message ?: '';
        $details = $messageData->get('description') ?? $failureObject->details ?: '';

        if ($details && $message && \strpos($details, $message) !== false) {
            $message = null;
        }

        if (\strpos($case->name, $suiteName) !== false) {
            $title = $case->name;
        }

        $inspectionName = $case->class ?: $case->classname ?: $failureObject->type ?: $severity;
        $inspectionId = ($this->globalPrefix ?: TeamCity::DEFAULT_INSPECTION_ID) . ':' . $inspectionName;

        $this->tcLogger->addInspectionType($inspectionId, $inspectionName, $this->globalPrefix);
        $this->tcLogger->addInspectionIssue(
            $inspectionId,
            $this->cleanFilepath((string)$case->file),
            $case->line,
            \trim(\implode("\n", \array_unique(\array_filter([
                \str_repeat('-', 120),
                $title,
                $message,
                $details
            ])))),
            $severity
        );
    }
}
