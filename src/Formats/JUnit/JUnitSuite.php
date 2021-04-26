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

namespace JBZoo\CiReportConverter\Formats\JUnit;

use JBZoo\CiReportConverter\Formats\AbstractNode;

/**
 * Class JUnitSuite
 * @package JBZoo\CiReportConverter\Formats\JUnit
 *
 * @property string|null $file
 *
 * @method self setFile(?string $file)
 */
class JUnitSuite extends AbstractNode
{
    /**
     * @var array
     */
    protected $meta = [
        'name' => ['string', 'required'],
        'file' => ['string'],
    ];

    /**
     * @var JUnitCase[]
     */
    private $testCases = [];

    /**
     * @var JUnitSuite[]
     */
    private $testSuites = [];

    /**
     * @param \DOMDocument $document
     * @return \DOMNode
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

        if (null !== $this->file) {
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
            $node->setAttribute('time', sprintf('%F', round($value, 6)));
        }

        foreach ($this->testSuites as $testSuite) {
            $node->appendChild($testSuite->toXML($document));
        }

        foreach ($this->testCases as $testCase) {
            $node->appendChild($testCase->toXML($document));
        }

        return $node;
    }

    /**
     * @param string $name
     * @return JUnitSuite
     */
    public function addSuite(string $name): self
    {
        $testSuite = new self($name);
        $this->testSuites[] = $testSuite;
        return $testSuite;
    }

    /**
     * @param string $name
     * @return JUnitCase
     */
    public function addCase(string $name): JUnitCase
    {
        $testCase = new JUnitCase($name);
        $this->testCases[] = $testCase;
        return $testCase;
    }

    /**
     * @return int
     */
    private function getAssertionsCount(): int
    {
        $result = 0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getAssertionsCount();
        }

        $result += (int)array_reduce($this->testCases, static function (int $acc, JUnitCase $testCase) {
            return $acc + $testCase->getAssertionsCount();
        }, 0);

        return $result;
    }

    /**
     * @return int
     */
    private function getErrorsCount(): int
    {
        $result = 0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getErrorsCount();
        }

        $result += (int)array_reduce($this->testCases, static function (int $acc, JUnitCase $testCase) {
            return $acc + $testCase->getErrorsCount();
        }, 0);

        return $result;
    }

    /**
     * @return int
     */
    private function getWarningsCount(): int
    {
        $result = 0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getWarningsCount();
        }

        $result += (int)array_reduce($this->testCases, static function (int $acc, JUnitCase $testCase) {
            return $acc + $testCase->getWarningsCount();
        }, 0);

        return $result;
    }

    /**
     * @return int
     */
    private function getFailuresCount(): int
    {
        $result = 0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getFailuresCount();
        }

        $result += (int)array_reduce($this->testCases, static function (int $acc, JUnitCase $testCase) {
            return $acc + $testCase->getFailuresCount();
        }, 0);

        return $result;
    }

    /**
     * @return int
     */
    private function getSkippedCount(): int
    {
        $result = 0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getSkippedCount();
        }

        $result += (int)array_reduce($this->testCases, static function (int $acc, JUnitCase $testCase) {
            return $acc + $testCase->getSkippedCount();
        }, 0);

        return $result;
    }

    /**
     * @return float
     */
    private function getTime(): float
    {
        $result = 0.0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getTime();
        }

        $result += array_reduce($this->testCases, static function (float $acc, JUnitCase $testCase) {
            return $acc + (float)$testCase->getTime();
        }, 0.0);

        return $result;
    }

    /**
     * @return int
     */
    private function getTestsCount(): int
    {
        $result = 0;

        foreach ($this->testSuites as $testSuite) {
            $result += $testSuite->getTestsCount();
        }

        return $result + count($this->testCases);
    }
}
