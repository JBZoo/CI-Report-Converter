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
use JBZoo\CIReportConverter\Formats\JUnit\CaseOutput\AbstractOutput;
use JBZoo\CIReportConverter\Formats\JUnit\CaseOutput\Error;
use JBZoo\CIReportConverter\Formats\JUnit\CaseOutput\Failure;
use JBZoo\CIReportConverter\Formats\JUnit\CaseOutput\Skipped;
use JBZoo\CIReportConverter\Formats\JUnit\CaseOutput\SystemOut;
use JBZoo\CIReportConverter\Formats\JUnit\CaseOutput\Warning;

/**
 * @property null|string $class
 * @property null|string $classname
 * @property null|string $file
 * @property null|int    $line
 * @property null|float  $time
 * @property null|int    $assertions
 * @method   self        setClass(?string $class)
 * @method   self        setClassname(?string $classname)
 * @method   self        setFile(?string $file)
 * @method   self        setLine(?string $line)
 * @method   self        setTime(?string $time)
 * @method   self        setAssertions(?string $assertions)
 */
final class JUnitCase extends AbstractNode
{
    /** @var AbstractOutput[] */
    public array $outputs = [];

    protected array $meta = [
        'name'       => ['string'],
        'class'      => ['string'],
        'classname'  => ['string'],
        'file'       => ['string'],
        'line'       => ['int'],
        'time'       => ['float'],
        'assertions' => ['int'],
    ];

    public function addFailure(?string $type = null, ?string $message = null, ?string $description = null): self
    {
        $this->outputs[] = new Failure($type ?? 'Failure', $message, $description);

        return $this;
    }

    public function addError(?string $type = null, ?string $message = null, ?string $description = null): self
    {
        $this->outputs[] = new Error($type ?? 'Error', $message, $description);

        return $this;
    }

    public function addWarning(?string $type = null, ?string $message = null, ?string $description = null): self
    {
        $this->outputs[] = new Warning($type ?? 'Warning', $message, $description);

        return $this;
    }

    public function addSystemOut(?string $description): self
    {
        if ($description !== null) {
            $this->outputs[] = (new SystemOut())->setDescription($description);
        } else {
            $this->outputs[] = (new SystemOut());
        }

        return $this;
    }

    public function markAsSkipped(): self
    {
        $this->outputs[] = new Skipped();

        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @phan-suppress PhanPossiblyNonClassMethodCall
     * @phan-suppress PhanPluginSuspiciousParamPositionInternal
     * @phan-suppress PhanPossiblyFalseTypeReturn
     */
    public function toXML(\DOMDocument $document): \DOMNode
    {
        $node = $document->createElement('testcase');

        $node->setAttribute('name', $this->name);

        if ($this->class !== null) {
            $node->setAttribute('class', $this->class);
        }

        if ($this->classname !== null) {
            $node->setAttribute('classname', $this->classname);
        }

        if ($this->file !== null) {
            $node->setAttribute('file', $this->file);
        }

        if ($this->line !== null) {
            $node->setAttribute('line', (string)$this->line);
        }

        if ($this->assertions !== null) {
            $node->setAttribute('assertions', (string)$this->assertions);
        }

        if ($this->time !== null) {
            $node->setAttribute('time', \sprintf('%F', \round($this->time, 6)));
        }

        foreach ($this->outputs as $caseOutput) {
            $node->appendChild($caseOutput->toXML($document));
        }

        return $node;
    }

    public function getTime(): ?float
    {
        return $this->time;
    }

    public function getAssertionsCount(): int
    {
        return (int)$this->assertions;
    }

    public function getErrorsCount(): int
    {
        return \count(\array_filter($this->outputs, static fn (AbstractOutput $output) => $output instanceof Error));
    }

    public function getWarningsCount(): int
    {
        return \count(\array_filter($this->outputs, static fn (AbstractOutput $output) => $output instanceof Warning));
    }

    public function getFailuresCount(): int
    {
        return \count(\array_filter($this->outputs, static fn (AbstractOutput $output) => $output instanceof Failure));
    }

    public function getSkippedCount(): int
    {
        return \count(\array_filter($this->outputs, static fn (AbstractOutput $output) => $output instanceof Skipped));
    }
}
