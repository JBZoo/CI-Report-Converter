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

namespace JBZoo\ToolboxCI\Formats\GithubActions;

use JBZoo\ToolboxCI\Formats\AbstractNode;

/**
 * Class GithubCase
 *
 * @property int|null    $line
 * @property int|null    $column
 * @property string|null $message
 * @property string      $level
 *
 * @package JBZoo\ToolboxCI\Formats\GithubActions
 */
class GithubCase extends AbstractNode
{
    public const LEVEL_ERROR   = 'error';
    public const LEVEL_WARNING = 'warning';
    public const LEVEL_DEBUG   = 'debug';

    public const DEFAULT_LEVEL   = self::LEVEL_ERROR;
    public const DEFAULT_MESSAGE = 'Undefined Error Message';

    /**
     * @var array
     */
    protected $meta = [
        'name'    => ['string'], // It's relative path to file
        'level'   => ['level'],  // error|warning|debug
        'line'    => ['int'],
        'column'  => ['int'],
        'message' => ['string'],
    ];

    /**
     * GithubCase constructor.
     * @param string|null $name
     */
    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        // Set default values
        $this->level = self::LEVEL_ERROR;
        $this->message = self::DEFAULT_MESSAGE;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $paramsAsString = implode(',', array_filter([
            $this->name ? "file={$this->name}" : null,
            $this->line ? "line={$this->line}" : null,
            $this->column ? "col={$this->column}" : null,
        ]));

        $paramsAsString = $paramsAsString ? " {$paramsAsString}" : '';
        $message = GithubActions::escape($this->message);

        return "::{$this->level}{$paramsAsString}::{$message}";
    }
}
