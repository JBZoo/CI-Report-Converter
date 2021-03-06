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

namespace JBZoo\CiReportConverter\Formats\TeamCity\Writers;

/**
 * Class Buffer
 * @package JBZoo\CiReportConverter\Formats\TeamCity\Writers
 */
class Buffer implements AbstractWriter
{
    /**
     * @var array
     */
    private array $buffer = [];

    /**
     * @inheritDoc
     */
    public function write(?string $message): void
    {
        $this->buffer[] = $message;
    }

    /**
     * @return array
     */
    public function getBuffer(): array
    {
        return $this->buffer;
    }
}
