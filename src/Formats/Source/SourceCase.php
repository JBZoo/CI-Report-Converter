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

/**
 * @property null|string $class
 * @property null|string $classname
 * @property null|string $file
 * @property null|int    $line
 * @property null|int    $column
 *
 * @property null|string $stdOut
 * @property null|string $errOut
 *
 * @property null|float  $time
 * @property null|int    $assertions
 * @property null|string $actual
 * @property null|string $expected
 *
 * @property null|SourceCaseOutput $failure
 * @property null|SourceCaseOutput $error
 * @property null|SourceCaseOutput $warning
 * @property null|SourceCaseOutput $skipped
 *
 * @method self setClass(?string $class)
 * @method self setClassname(?string $classname)
 * @method self setFile(?string $file)
 * @method self setLine(?string $line)
 *
 * @method self setStdOut(?string $stdOut)
 * @method self setErrOut(?string $errOut)
 *
 * @method self setAssertions(?string $assertions)
 * @method self setTime(?string $time)
 * @method self setActual(?string $actual)
 * @method self setExpected(?string $expected)
 *
 * @method self setFailure(?SourceCaseOutput $failure)
 * @method self setError(?SourceCaseOutput $error)
 * @method self setWarning(?SourceCaseOutput $warning)
 * @method self setSkipped(?SourceCaseOutput $skipped)
 */
final class SourceCase extends AbstractNode
{
    protected array $meta = [
        'name' => ['string'],

        // Location
        'class'     => ['string'],
        'classname' => ['string'],
        'file'      => ['string'],
        'line'      => ['int'],
        'column'    => ['int'],

        // Output
        'stdOut' => ['string'],
        'errOut' => ['string'],

        // Test meta data
        'time'       => ['float'],
        'assertions' => ['int'],
        'actual'     => ['string'],
        'expected'   => ['string'],

        // Type of negative result
        'failure' => [SourceCaseOutput::class],
        'error'   => [SourceCaseOutput::class],
        'warning' => [SourceCaseOutput::class],
        'skipped' => [SourceCaseOutput::class],
    ];

    public function getTime(int $round = 6): ?string
    {
        return $this->time === null ? null : (string)\round($this->time, $round);
    }

    public function isError(): bool
    {
        return $this->error !== null;
    }

    public function isWarning(): bool
    {
        return $this->warning !== null;
    }

    public function isFailure(): bool
    {
        return $this->failure !== null;
    }

    public function isSkipped(): bool
    {
        return $this->skipped !== null;
    }

    public function isComparison(): bool
    {
        return $this->actual !== null && $this->expected !== null;
    }

    public function getMessage(): ?string
    {
        $message = null;
        if ($this->stdOut !== null) {
            $message = self::buildMessage([$this->name, $this->stdOut]);
        } elseif ($this->errOut !== null) {
            $message = self::buildMessage([$this->name, $this->errOut]);
        } elseif ($this->failure !== null) {
            $message = $this->failure->details !== ''
                ? self::buildMessage([$this->failure->details])
                : self::buildMessage([$this->failure->message]);
        } elseif ($this->error !== null) {
            $message = $this->error->details !== ''
                ? self::buildMessage([$this->error->details])
                : self::buildMessage([$this->error->message]);
        } elseif ($this->warning !== null) {
            $message = $this->warning->details !== ''
                ? self::buildMessage([$this->warning->details])
                : self::buildMessage([$this->warning->message]);
        } elseif ($this->skipped !== null) {
            $message = $this->skipped->details !== ''
                ? self::buildMessage([$this->skipped->details])
                : self::buildMessage([$this->skipped->message]);
            $message ??= 'Skipped';
        }

        return $message !== '' ? $message : null;
    }

    private static function buildMessage(array $parts): ?string
    {
        $parts   = \array_filter($parts, '\is_string');
        $parts   = \array_map('\trim', $parts);
        $parts   = \array_filter($parts);
        $parts   = \array_unique($parts);
        $message = \implode("\n", $parts);
        $message = \trim($message);

        return $message !== '' ? $message : null;
    }
}
