<?php

/**
 * JBZoo Toolbox - Toolbox-CI
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Toolbox-CI
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Toolbox-CI
 */

declare(strict_types=1);

namespace JBZoo\ToolboxCI\Formats\Source;

use JBZoo\ToolboxCI\Formats\AbstractNode;

/**
 * Class SourceCase
 * @package JBZoo\ToolboxCI\Formats\Source
 *
 * @property string|null           $class
 * @property string|null           $classname
 * @property string|null           $file
 * @property int|null              $line
 * @property int|null              $column
 *
 * @property string|null           $stdOut
 * @property string|null           $errOut
 *
 * @property float|null            $time
 * @property int|null              $assertions
 * @property string|null           $actual
 * @property string|null           $expected
 *
 * @property SourceCaseOutput|null $failure
 * @property SourceCaseOutput|null $error
 * @property SourceCaseOutput|null $warning
 * @property SourceCaseOutput|null $skipped
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
class SourceCase extends AbstractNode
{
    /**
     * @var array
     */
    protected $meta = [
        'name'       => ['string'],

        // Location
        'class'      => ['string'],
        'classname'  => ['string'],
        'file'       => ['string'],
        'line'       => ['int'],
        'column'     => ['int'],

        // Output
        'stdOut'     => ['string'],
        'errOut'     => ['string'],

        // Test meta data
        'time'       => ['float'],
        'assertions' => ['int'],
        'actual'     => ['string'],
        'expected'   => ['string'],

        // Type of negative result
        'failure'    => [SourceCaseOutput::class],
        'error'      => [SourceCaseOutput::class],
        'warning'    => [SourceCaseOutput::class],
        'skipped'    => [SourceCaseOutput::class],
    ];

    /**
     * @param int $round
     * @return string|null
     */
    public function getTime(int $round = 6): ?string
    {
        return $this->time === null ? null : (string)round($this->time, $round);
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->error !== null;
    }

    /**
     * @return bool
     */
    public function isWarning(): bool
    {
        return $this->warning !== null;
    }

    /**
     * @return bool
     */
    public function isFailure(): bool
    {
        return $this->failure !== null;
    }

    /**
     * @return bool
     */
    public function isSkipped(): bool
    {
        return $this->skipped !== null;
    }

    /**
     * @return bool
     */
    public function isComparison(): bool
    {
        return $this->actual !== null && $this->expected !== null;
    }
}
