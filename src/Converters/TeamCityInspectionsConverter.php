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

class TeamCityInspectionsConverter extends AbstractConverter
{
    public const TYPE = 'tc-inspections';
    public const NAME = 'TeamCity - Inspections';

    private TeamCity $tcLogger;
    private string   $globalPrefix = '';

    public function __construct(array $params = [], ?int $flowId = null, ?AbstractWriter $tcWriter = null)
    {
        $this->tcLogger = new TeamCity($tcWriter ?? new Buffer(), $flowId, $params);
    }

    public function fromInternal(SourceSuite $sourceSuite): string
    {
        if ($this->flowId > 0) {
            $this->tcLogger->setFlowId($this->flowId);
        }

        $this->globalPrefix = \trim($sourceSuite->name) !== ''
            ? \trim($sourceSuite->name)
            : TeamCity::DEFAULT_INSPECTION_ID;

        $this->renderSuite($sourceSuite);

        $buffer = $this->tcLogger->getWriter();
        if ($buffer instanceof Buffer) {
            return \implode('', $buffer->getBuffer());
        }

        return '';
    }

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
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function renderTestCase(SourceCase $case, string $suiteName): void
    {
        $failureObject = null;
        $severity      = null;

        if ($case->failure !== null) {
            $severity      = TeamCity::SEVERITY_ERROR;
            $failureObject = $case->failure;
        } elseif ($case->error !== null) {
            $severity      = TeamCity::SEVERITY_ERROR;
            $failureObject = $case->error;
        } elseif ($case->warning !== null) {
            $severity      = TeamCity::SEVERITY_WARNING;
            $failureObject = $case->warning;
        } elseif ($case->skipped !== null) {
            $severity      = TeamCity::SEVERITY_WARNING_WEAK;
            $failureObject = $case->skipped;
        }

        if ($failureObject === null) {
            return;
        }

        $messageData = $failureObject->parseDescription();

        $title   = "{$suiteName} / {$case->name}";
        $message = $messageData->getStringNull('message') ?? $failureObject->message ?? '';
        $details = $messageData->getStringNull('description') ?? $failureObject->details ?? '';

        if ($details !== '' && $message !== '' && \str_contains($details, $message)) {
            $message = null;
        }

        if (\str_contains($case->name, $suiteName)) {
            $title = $case->name;
        }

        $inspectionName = $case->class ?? $case->classname ?? $failureObject->type ?? $severity;
        $inspectionId   = ($this->globalPrefix !== ''
                ? $this->globalPrefix
                : TeamCity::DEFAULT_INSPECTION_ID) . ':' . $inspectionName;

        $this->tcLogger->addInspectionType($inspectionId, $inspectionName, $this->globalPrefix);
        $this->tcLogger->addInspectionIssue(
            $inspectionId,
            $this->cleanFilepath((string)$case->file),
            $case->line,
            \trim(
                \implode(
                    "\n",
                    \array_unique(\array_filter([\str_repeat('-', 120), $title, $message, $details])),
                ),
            ),
            $severity,
        );
    }
}
