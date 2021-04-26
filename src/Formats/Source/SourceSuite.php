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

namespace JBZoo\CiReportConverter\Formats\Source;

use JBZoo\CiReportConverter\Formats\AbstractNode;

/**
 * Class SourceSuite
 * @package JBZoo\CiReportConverter\Formats\Source
 *
 * @property string|null $file
 * @property string|null $class
 *
 * @method self setFile(?string $file)
 * @method self setClass(?string $class)
 */
class SourceSuite extends AbstractNode
{
    /**
     * @var array
     */
    protected $meta = [
        'name'  => ['string'],
        'file'  => ['string'],
        'class' => ['string'],
    ];

    /**
     * @var SourceCase[]
     */
    private $cases = [];

    /**
     * @var SourceSuite[]
     */
    private $suites = [];

    /**
     * @return bool
     */
    public function hasSubSuites(): bool
    {
        return count($this->suites) > 0;
    }

    /**
     * @return SourceSuite[]
     */
    public function getSuites(): array
    {
        return $this->suites;
    }

    /**
     * @return SourceCase[]
     */
    public function getCases(): array
    {
        return $this->cases;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->getCasesCount() === 0;
    }

    /**
     * @param string $testSuiteName
     * @return SourceSuite
     */
    public function addSuite(string $testSuiteName): self
    {
        if (!array_key_exists($testSuiteName, $this->suites)) {
            $testSuite = new self($testSuiteName);
            $this->suites[$testSuiteName] = $testSuite;
        }

        return $this->suites[$testSuiteName];
    }

    /**
     * @param string $testCaseName
     * @return SourceCase
     */
    public function addTestCase(string $testCaseName): SourceCase
    {
        $testCase = new SourceCase($testCaseName);
        $this->cases[] = $testCase;
        return $testCase;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = array_filter(array_merge(parent::toArray(), [
            'time'       => $this->getTime(),
            'tests'      => $this->getCasesCount(),
            'assertions' => $this->getAssertionsCount(),
            'errors'     => $this->getErrorsCount(),
            'warnings'   => $this->getWarningCount(),
            'failure'    => $this->getFailureCount(),
            'skipped'    => $this->getSkippedCount(),
        ]), static function ($value) {
            return $value !== null;
        });

        $result = [
            'data'   => $data,
            'cases'  => [],
            'suites' => [],
        ];

        foreach ($this->suites as $suite) {
            $result['suites'][] = $suite->toArray();
        }

        foreach ($this->cases as $case) {
            $result['cases'][] = $case->toArray();
        }

        return $result;
    }

    /**
     * @param int $round
     * @return float|null
     */
    public function getTime(int $round = 6): ?float
    {
        $result = 0.0;

        foreach ($this->suites as $suite) {
            $result += (float)$suite->getTime();
        }

        foreach ($this->cases as $case) {
            $result += (float)$case->getTime();
        }

        return $result === 0.0 ? null : round($result, $round);
    }

    /**
     * @return int|null
     */
    public function getCasesCount(): ?int
    {
        $subResult = 0;

        foreach ($this->suites as $suite) {
            $subResult += (int)$suite->getCasesCount();
        }

        $result = count($this->cases) + $subResult;

        return $result === 0 ? null : $result;
    }

    /**
     * @return int|null
     */
    public function getAssertionsCount(): ?int
    {
        $result = 0;

        foreach ($this->suites as $suite) {
            $result += (int)$suite->getAssertionsCount();
        }

        foreach ($this->cases as $case) {
            $result += (int)$case->assertions;
        }

        return $result === 0 ? null : $result;
    }

    /**
     * @return int|null
     */
    public function getErrorsCount(): ?int
    {
        $result = 0;

        foreach ($this->suites as $suite) {
            $result += (int)$suite->getErrorsCount();
        }

        foreach ($this->cases as $case) {
            $result += $case->isError() ? 1 : 0;
        }

        return $result === 0 ? null : $result;
    }

    /**
     * @return int|null
     */
    public function getWarningCount(): ?int
    {
        $result = 0;

        foreach ($this->suites as $suite) {
            $result += (int)$suite->getWarningCount();
        }

        foreach ($this->cases as $case) {
            $result += $case->isWarning() ? 1 : 0;
        }

        return $result === 0 ? null : $result;
    }

    /**
     * @return int|null
     */
    public function getFailureCount(): ?int
    {
        $result = 0;

        foreach ($this->suites as $suite) {
            $result += (int)$suite->getFailureCount();
        }

        foreach ($this->cases as $case) {
            $result += $case->isFailure() ? 1 : 0;
        }

        return $result === 0 ? null : $result;
    }

    /**
     * @return int|null
     */
    public function getSkippedCount(): ?int
    {
        $result = 0;

        foreach ($this->suites as $suite) {
            $result += (int)$suite->getSkippedCount();
        }

        foreach ($this->cases as $case) {
            $result += $case->isSkipped() ? 1 : 0;
        }

        return $result === 0 ? null : $result;
    }
}
