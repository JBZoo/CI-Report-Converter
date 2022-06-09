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

namespace JBZoo\CiReportConverter\Formats\TeamCity;

use JBZoo\CiReportConverter\Formats\TeamCity\Writers\AbstractWriter;
use JBZoo\Utils\Sys;

/**
 * Class TeamCity
 * @package JBZoo\CiReportConverter\Formats\TeamCity
 */
class TeamCity
{
    public const SEVERITY_INFO         = 'INFO';
    public const SEVERITY_WARNING_WEAK = 'WEAK WARNING';
    public const SEVERITY_WARNING      = 'WARNING';
    public const SEVERITY_ERROR        = 'ERROR';

    public const DEFAULT_INSPECTION_ID       = 'CodingStandardIssues';
    public const DEFAULT_INSPECTION_NAME     = 'Coding Standard Issues';
    public const DEFAULT_INSPECTION_CATEGORY = 'Coding Standard';
    public const DEFAULT_INSPECTION_DESC     = 'Issues found while checking coding standards';

    /**
     * @var int|null
     */
    private ?int $flowId = null;

    /**
     * @var AbstractWriter
     */
    private AbstractWriter $writer;

    /**
     * @var array
     */
    private $params = [
        'show-datetime' => true
    ];

    /**
     * @var array
     */
    private array $inspectionTypes = [];

    /**
     * @param AbstractWriter $writer The writer used to write messages.
     * @param int|null       $flowId The flow ID or `null`.
     * @param array          $params
     */
    public function __construct(AbstractWriter $writer, ?int $flowId = null, array $params = [])
    {
        $flowId = (int)$flowId ?: null;
        if (null === $flowId && Sys::isFunc('getmypid')) {
            $this->flowId = (int)\getmypid() ?: null;
        } else {
            $this->flowId = $flowId;
        }

        $this->writer = $writer;
        $this->params = \array_merge($this->params, $params);
    }

    /**
     * @param int|null $flowId
     * @return $this
     */
    public function setFlowId(?int $flowId): self
    {
        $this->flowId = $flowId;
        return $this;
    }

    /**
     * Returns the writer.
     *
     * @return AbstractWriter The writer instance.
     */
    public function getWriter(): AbstractWriter
    {
        return $this->writer;
    }

    /**
     * @param string $name The test suite name.
     * @param array  $params
     */
    public function testSuiteStarted(string $name, array $params = []): void
    {
        $this->write('testSuiteStarted', \array_merge(['name' => $name], $params));
    }

    /**
     * @param string $name The test suite name.
     * @param array  $params
     */
    public function testSuiteFinished(string $name, array $params = []): void
    {
        $this->write('testSuiteFinished', \array_merge(['name' => $name], $params));
    }

    /**
     * @param string $messageName
     * @param array  $parameters Parameters with value === `null` will be filtered out.
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
        $parameters = \array_filter($parameters, static function ($value) {
            return $value !== null && $value !== '' && $value !== ' ';
        });

        $this->writer->write(Helper::printEvent($messageName, $parameters));
    }

    /**
     * @param string $name
     * @param array  $params
     */
    public function testStarted(string $name, array $params = []): void
    {
        $this->write('testStarted', \array_merge(['name' => $name], $params));
    }

    /**
     * @param string     $name     The test name.
     * @param float|null $duration The test duration in seconds.
     */
    public function testFinished(string $name, ?float $duration = null): void
    {
        $this->write('testFinished', [
            'name'     => $name,
            'duration' => $duration > 0 ? \round($duration * 1000) : null,
        ]);
    }

    /**
     * @param string $name
     * @param array  $params
     */
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

        if (null !== $writeParams['actual'] && null !== $writeParams['expected']) {
            $writeParams['type'] = 'comparisonFailure';
        }

        $this->write('testFailed', $writeParams);
    }

    /**
     * @param string      $name
     * @param string|null $message
     * @param string|null $details
     * @param float|null  $duration
     */
    public function testSkipped(
        string $name,
        ?string $message = null,
        ?string $details = null,
        ?float $duration = null
    ): void {
        $this->write('testIgnored', [
            'name'     => $name,
            'message'  => $message,
            'details'  => $details,
            'duration' => $duration > 0 ? \round($duration * 1000) : null,
        ]);
    }

    /**
     * @param string      $inspectionId
     * @param string|null $name
     * @param string|null $category
     * @param string|null $description
     */
    public function addInspectionType(
        string $inspectionId,
        ?string $name = null,
        ?string $category = null,
        ?string $description = null
    ): void {
        $cleanInspectionId = \trim($inspectionId);
        if (!$cleanInspectionId) {
            throw new Exception("Inspection Id can't be empty");
        }

        if (!\in_array($inspectionId, $this->inspectionTypes, true)) {
            $this->write('inspectionType', [
                'id'          => $cleanInspectionId,
                'name'        => $name ?: self::DEFAULT_INSPECTION_NAME,
                'category'    => $category ?: self::DEFAULT_INSPECTION_CATEGORY,
                'description' => $description ?: self::DEFAULT_INSPECTION_DESC,
            ]);

            $this->inspectionTypes[] = $inspectionId;
        }
    }

    public function addDefaultInspectionType(): void
    {
        $this->addInspectionType(self::DEFAULT_INSPECTION_ID);
    }

    /**
     * @param string      $typeId
     * @param string|null $filename
     * @param int|null    $line
     * @param string      $message
     * @param string      $severity
     */
    public function addInspectionIssue(
        string $typeId,
        ?string $filename,
        ?int $line,
        string $message,
        string $severity = self::SEVERITY_WARNING
    ): void {
        $this->write('inspection', [
            'typeId'   => $typeId,
            'file'     => $filename ?: 'Undefined file',
            'line'     => $line ?: 0,
            'message'  => $message,
            // Custom props
            'SEVERITY' => $severity,
        ]);
    }
}
