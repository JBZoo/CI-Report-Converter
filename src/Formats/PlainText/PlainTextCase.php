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

namespace JBZoo\CIReportConverter\Formats\PlainText;

use JBZoo\CIReportConverter\Formats\AbstractNode;

final class PlainTextCase extends AbstractNode
{
    public const LEVEL_ERROR   = 'Error';
    public const LEVEL_WARNING = 'Warning';
    public const LEVEL_DEBUG   = 'Debug';

    public const DEFAULT_LEVEL   = self::LEVEL_ERROR;
    public const DEFAULT_MESSAGE = 'Undefined Error Message';

    // `$this->name` is relative path to file
    public ?int    $line    = null;
    public ?int    $column  = null;
    public ?string $message = null;
    public string  $level; // See self::LEVEL_*

    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        // Set default values
        $this->level   = self::DEFAULT_LEVEL;
        $this->message = self::DEFAULT_MESSAGE;
        $this->column  = 1;
        $this->line    = 1;
    }
}
