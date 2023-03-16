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

namespace JBZoo\CIReportConverter\Formats\JUnit;

use JBZoo\CIReportConverter\Formats\AbstractNode;

/**
 * @property null|string $file
 *
 * @method self setFile(?string $file)
 */
class JUnitSuite extends AbstractNode
{
    protected array $meta = [
        'name' => ['string', 'required'],
        'file' => ['string'],
    ];

    /** @var JUnitCase[] */
    private array $testCases = [];

    /** @var JUnitSuite[] */
    private array $testSuites = [];

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @phan-suppress PhanPossiblyNonClassMethodCall
     * @phan-suppress PhanPluginSuspiciousParamPositionInternal
     * @phan-suppress PhanPossiblyFalseTypeReturn
     */
    public function toXML(\DOMDocument $document): \DOMNode
    {
        $node = $document->createElement('testsuite');

        $node->setAttribute('name', $this->name);

        if ($this->file !== null) {
            $node->setAttribute('file', $this->file);
        }

        if ($value = $this->getTestsCount()) {
            $node->setAttribute('tests', (string)$value);
        }

        if ($value = $this->getAssertionsCount()) {
            $node->setAttribute('assertions', (string)$value);
        }

        if ($value = $this->getErrorsCount()) {
            $node->setAttribute('errors', (string)$value);
        }

        if ($value = $this->getWarningsCount()) {
            $node->setAttribute('warnings', (string)$value);
        }

        if ($value = $this->getFailuresCount()) {
            $node->setAttribute('failures', (string)$value);
        }

        if ($value = $this->getSkippedCount()) {
            $node->setAttribute('skipped', (string)$value);
        }

        if ($value = $this->getTime()) {
            $node->setAttribute('time', \sprintf('%F', \round($value, 6)));
        }

        foreach ($this->testSuites as $testSuite) {
            $node->appendChild($testSuite->toXML($document));
        }

        foreach ($this->testCases as $testCase) {
            $node->appendChild($testCase->toXML($document));
        }

        return $node;
    }

    public function addSuite(string $name): self
    {
        $testSuite          = new self($name);
        $this->testSuites[] = $testSuite;

        return $testSuite;
    }

    public function addCase(string $name): JUnitCase
    {
        $testCase          = new JUnitCase($name);
        $this->testCases[] = $testCase;

        return $testCase;
    }

    private function getAssertionsCount(): int
    {
        $result = 0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getAssertionsCount();
        }

        $result += (int)\array_reduce(
            $this->testCases,
            static fn (int $acc, JUnitCase $testCase) => $acc + $testCase->getAssertionsCount(),
            0,
        );

        return $result;
    }

    private function getErrorsCount(): int
    {
        $result = 0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getErrorsCount();
        }

        $result += (int)\array_reduce(
            $this->testCases,
            static fn (int $acc, JUnitCase $testCase) => $acc + $testCase->getErrorsCount(),
            0,
        );

        return $result;
    }

    private function getWarningsCount(): int
    {
        $result = 0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getWarningsCount();
        }

        $result += (int)\array_reduce(
            $this->testCases,
            static fn (int $acc, JUnitCase $testCase) => $acc + $testCase->getWarningsCount(),
            0,
        );

        return $result;
    }

    private function getFailuresCount(): int
    {
        $result = 0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getFailuresCount();
        }

        $result += (int)\array_reduce(
            $this->testCases,
            static fn (int $acc, JUnitCase $testCase) => $acc + $testCase->getFailuresCount(),
            0,
        );

        return $result;
    }

    private function getSkippedCount(): int
    {
        $result = 0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getSkippedCount();
        }

        $result += (int)\array_reduce(
            $this->testCases,
            static fn (int $acc, JUnitCase $testCase) => $acc + $testCase->getSkippedCount(),
            0,
        );

        return $result;
    }

    private function getTime(): float
    {
        $result = 0.0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getTime();
        }

        $result += \array_reduce(
            $this->testCases,
            static fn (float $acc, JUnitCase $testCase) => $acc + (float)$testCase->getTime(),
            0.0,
        );

        return $result;
    }

    private function getTestsCount(): int
    {
        $result = 0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getTestsCount();
        }

        return $result + \count($this->testCases);
    }
}
