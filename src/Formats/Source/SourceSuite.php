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

namespace JBZoo\CIReportConverter\Formats\Source;

use JBZoo\CIReportConverter\Formats\AbstractNode;

final class SourceSuite extends AbstractNode
{
    public ?string $file = null;
    public ?string $class = null;

    /** @var SourceCase[] */
    private array $cases = [];

    /** @var SourceSuite[] */
    private array $suites = [];

    public function hasSubSuites(): bool
    {
        return \count($this->suites) > 0;
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

    public function isEmpty(): bool
    {
        return $this->getCasesCount() === 0;
    }

    public function addSuite(string $testSuiteName): self
    {
        if (!\array_key_exists($testSuiteName, $this->suites)) {
            $testSuite = new self($testSuiteName);
            $this->suites[$testSuiteName] = $testSuite;
        }

        return $this->suites[$testSuiteName];
    }

    public function addTestCase(string $testCaseName): SourceCase
    {
        $testCase = new SourceCase($testCaseName);
        $this->cases[] = $testCase;

        return $testCase;
    }

    public function toArray(): array
    {
        $data = \array_filter(
            \array_merge(parent::toArray(), [
                'time'       => $this->getTime(),
                'tests'      => $this->getCasesCount(),
                'assertions' => $this->getAssertionsCount(),
                'errors'     => $this->getErrorsCount(),
                'warnings'   => $this->getWarningCount(),
                'failure'    => $this->getFailureCount(),
                'skipped'    => $this->getSkippedCount(),
            ]),
            static fn ($value) => $value !== null,
        );

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

    public function getTime(int $round = 6): ?float
    {
        $result = 0.0;

        foreach ($this->suites as $suite) {
            $result += (float)$suite->getTime();
        }

        foreach ($this->cases as $case) {
            $result += (float)$case->getTime();
        }

        return $result === 0.0 ? null : \round($result, $round);
    }

    public function getCasesCount(): ?int
    {
        $subResult = 0;

        foreach ($this->suites as $suite) {
            $subResult += (int)$suite->getCasesCount();
        }

        $result = \count($this->cases) + $subResult;

        return $result === 0 ? null : $result;
    }

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
