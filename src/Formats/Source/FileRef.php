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

namespace JBZoo\CiReportConverter\Formats\Source;

use JBZoo\CiReportConverter\Formats\AbstractNode;

/**
 * Class FileRef
 * @package JBZoo\CiReportConverter\Formats\Source
 *
 * @property string      $name
 * @property string|null $fullpath
 * @property int|null    $line
 * @property int|null    $column
 */
class FileRef extends AbstractNode
{
    /**
     * @var array
     */
    protected $meta = [
        'name'     => ['string'], // It's relative path to file
        'fullpath' => ['string'],
        'line'     => ['int'],
    ];

    /**
     * @return string
     */
    public function getFullName(): string
    {
        $result = (string)$this->fullpath;
        if ($this->line > 0) {
            $result .= ":{$this->line}";
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getShortName(): string
    {
        $result = $this->name;
        if ($this->line > 0) {
            $result .= " on line {$this->line}";
        }

        return $result;
    }
}
