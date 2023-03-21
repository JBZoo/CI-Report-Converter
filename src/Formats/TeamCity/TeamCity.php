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

namespace JBZoo\CIReportConverter\Formats\TeamCity;

use JBZoo\CIReportConverter\Formats\TeamCity\Writers\AbstractWriter;
use JBZoo\Utils\Sys;

final class TeamCity
{
    public const SEVERITY_INFO = 'INFO';
    public const SEVERITY_WARNING_WEAK = 'WEAK WARNING';
    public const SEVERITY_WARNING = 'WARNING';
    public const SEVERITY_ERROR = 'ERROR';

    public const DEFAULT_INSPECTION_ID = 'CodingStandardIssues';
    public const DEFAULT_INSPECTION_NAME = 'Coding Standard Issues';
    public const DEFAULT_INSPECTION_CATEGORY = 'Coding Standard';
    public const DEFAULT_INSPECTION_DESC = 'Issues found while checking coding standards';

    private ?int $flowId = null;

    private AbstractWriter $writer;

    /** @var array */
    private $params = [
        'show-datetime' => true,
    ];

    private array $inspectionTypes = [];

    /**
     * @param AbstractWriter $writer the writer used to write messages
     * @param null|int       $flowId the flow ID or `null`
     */
    public function __construct(AbstractWriter $writer, ?int $flowId = null, array $params = [])
    {
        $flowId = $flowId > 0 ? $flowId : null;
        if ($flowId === null && Sys::isFunc('getmypid')) {
            $this->flowId = \getmypid() !== false ? \getmypid() : null;
        } else {
            $this->flowId = $flowId;
        }

        $this->writer = $writer;
        $this->params = \array_merge($this->params, $params);
    }

    public function setFlowId(?int $flowId): self
    {
        $this->flowId = $flowId;

        return $this;
    }

    /**
     * Returns the writer.
     *
     * @return AbstractWriter the writer instance
     */
    public function getWriter(): AbstractWriter
    {
        return $this->writer;
    }

    /**
     * @param string $name the test suite name
     */
    public function testSuiteStarted(string $name, array $params = []): void
    {
        $this->write('testSuiteStarted', \array_merge(['name' => $name], $params));
    }

    /**
     * @param string $name the test suite name
     */
    public function testSuiteFinished(string $name, array $params = []): void
    {
        $this->write('testSuiteFinished', \array_merge(['name' => $name], $params));
    }

    /**
     * @param array $parameters parameters with value === `null` will be filtered out
     */
    public function write(string $messageName, array $parameters): void
    {
        $parameters = \array_merge($parameters, [
            'timestamp' => Helper::formatTimestamp(),
            'flowId'    => $this->flowId,
        ]);

        if (!$this->params['show-datetime']) {
            unset($parameters['timestamp']);
        }

        // Filter out optional parameters.
        $parameters = \array_filter(
            $parameters,
            static fn ($value) => $value !== null && $value !== '' && $value !== ' ',
        );

        $this->writer->write(Helper::printEvent($messageName, $parameters));
    }

    public function testStarted(string $name, array $params = []): void
    {
        $this->write('testStarted', \array_merge(['name' => $name], $params));
    }

    /**
     * @param string     $name     the test name
     * @param null|float $duration the test duration in seconds
     */
    public function testFinished(string $name, ?float $duration = null): void
    {
        $this->write('testFinished', [
            'name'     => $name,
            'duration' => $duration > 0 ? \round($duration * 1000) : null,
        ]);
    }

    public function testFailed(string $name, array $params = []): void
    {
        $writeParams = [
            'name'     => $name,
            'message'  => $params['message'] ?? null,
            'details'  => $params['details'] ?? null,
            'duration' => $params['duration'] > 0 ? \round($params['duration'] * 1000) : null,
            'type'     => null,
            'actual'   => $params['actual'] ?? null,
            'expected' => $params['expected'] ?? null,
        ];

        if ($writeParams['actual'] !== null && $writeParams['expected'] !== null) {
            $writeParams['type'] = 'comparisonFailure';
        }

        $this->write('testFailed', $writeParams);
    }

    public function testSkipped(
        string $name,
        ?string $message = null,
        ?string $details = null,
        ?float $duration = null,
    ): void {
        $this->write('testIgnored', [
            'name'     => $name,
            'message'  => $message,
            'details'  => $details,
            'duration' => $duration > 0 ? \round($duration * 1000) : null,
        ]);
    }

    public function addInspectionType(
        string $inspectionId,
        ?string $name = null,
        ?string $category = null,
        ?string $description = null,
    ): void {
        $cleanInspectionId = \trim($inspectionId);
        if ($cleanInspectionId === '') {
            throw new Exception("Inspection Id can't be empty");
        }

        if (!\in_array($inspectionId, $this->inspectionTypes, true)) {
            $this->write('inspectionType', [
                'id'          => $cleanInspectionId,
                'name'        => $name ?? self::DEFAULT_INSPECTION_NAME,
                'category'    => $category ?? self::DEFAULT_INSPECTION_CATEGORY,
                'description' => $description ?? self::DEFAULT_INSPECTION_DESC,
            ]);

            $this->inspectionTypes[] = $inspectionId;
        }
    }

    public function addDefaultInspectionType(): void
    {
        $this->addInspectionType(self::DEFAULT_INSPECTION_ID);
    }

    public function addInspectionIssue(
        string $typeId,
        ?string $filename,
        ?int $line,
        string $message,
        string $severity = self::SEVERITY_WARNING,
    ): void {
        $this->write('inspection', [
            'typeId'  => $typeId,
            'file'    => $filename ?? 'Undefined file',
            'line'    => $line ?? 0,
            'message' => $message,
            // Custom props
            'SEVERITY' => $severity,
        ]);
    }
}
