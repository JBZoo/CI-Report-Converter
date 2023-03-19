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

namespace JBZoo\CIReportConverter\Formats\GithubActions;

use JBZoo\CIReportConverter\Formats\AbstractNode;

/**
 * @property null|int    $line
 * @property null|int    $column
 * @property null|string $message
 * @property string      $level
 */
class GithubCase extends AbstractNode
{
    public const LEVEL_ERROR   = 'error';
    public const LEVEL_WARNING = 'warning';
    public const LEVEL_DEBUG   = 'debug';

    public const DEFAULT_LEVEL   = self::LEVEL_ERROR;
    public const DEFAULT_MESSAGE = 'Undefined Error Message';

    protected array $meta = [
        'name'    => ['string'], // Relative path to file
        'level'   => ['string'], // See self::LEVEL_*
        'line'    => ['int'],
        'column'  => ['int'],
        'message' => ['string'],
    ];

    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        // Set default values
        $this->level   = self::DEFAULT_LEVEL;
        $this->message = self::DEFAULT_MESSAGE;
    }

    public function __toString(): string
    {
        $paramsAsString = \implode(
            ',',
            \array_filter([
                $this->name !== '' ? "file={$this->name}" : null,
                $this->line !== 0 && $this->line !== null ? "line={$this->line}" : null,
                $this->column !== 0 && $this->column !== null ? "col={$this->column}" : null,
            ]),
        );

        $paramsAsString = $paramsAsString !== '' ? " {$paramsAsString}" : '';
        $message        = GithubActions::escape($this->message);

        return "::{$this->level}{$paramsAsString}::{$message}\n";
    }
}
