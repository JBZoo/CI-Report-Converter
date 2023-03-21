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

final class SourceCase extends AbstractNode
{
    // Location
    public ?string $class     = null;
    public ?string $classname = null;
    public ?string $file      = null;
    public ?int    $line      = null;
    public ?int    $column    = null;

    // Output
    public ?string $stdOut = null;
    public ?string $errOut = null;

    // Test meta data
    public ?float  $time       = null;
    public ?int    $assertions = null;
    public ?string $actual     = null;
    public ?string $expected   = null;

    // Type of negative results
    public ?SourceCaseOutput $failure = null;
    public ?SourceCaseOutput $error   = null;
    public ?SourceCaseOutput $warning = null;
    public ?SourceCaseOutput $skipped = null;

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
