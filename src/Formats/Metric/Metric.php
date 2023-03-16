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

namespace JBZoo\CIReportConverter\Formats\Metric;

use JBZoo\CIReportConverter\Formats\AbstractNode;

/**
 * @property null|string $name
 * @property null|string $key
 * @property null|string $description
 * @property null|float  $value
 */
class Metric extends AbstractNode
{
    protected array $meta = [
        'key'         => ['string'],
        'name'        => ['string'],
        'description' => ['string'],
        'value'       => ['float'],
    ];
}
