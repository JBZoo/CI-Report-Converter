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

namespace JBZoo\CiReportConverter\Formats\PlainText;

use JBZoo\CiReportConverter\Formats\AbstractNode;

/**
 * Class PlainTextCase
 *
 * @property int|null    $line
 * @property int|null    $column
 * @property string|null $message
 * @property string      $level
 *
 * @package JBZoo\CiReportConverter\Formats\PlainText
 */
class PlainTextCase extends AbstractNode
{
    public const LEVEL_ERROR   = 'Error';
    public const LEVEL_WARNING = 'Warning';
    public const LEVEL_DEBUG   = 'Debug';

    public const DEFAULT_LEVEL   = self::LEVEL_ERROR;
    public const DEFAULT_MESSAGE = 'Undefined Error Message';

    /**
     * @var array
     */
    protected array $meta = [
        'name'    => ['string'], // It's relative path to file
        'level'   => ['string'], // See self::LEVEL_*
        'line'    => ['int'],
        'column'  => ['int'],
        'message' => ['string'],
    ];

    /**
     * PlainTextCase constructor.
     * @param string|null $name
     */
    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        // Set default values
        $this->level = self::DEFAULT_LEVEL;
        $this->message = self::DEFAULT_MESSAGE;
        $this->column = 1;
        $this->line = 1;
    }
}
