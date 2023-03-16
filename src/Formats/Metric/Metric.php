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
 * Class Metric
 *
 * @property string|null $name
 * @property string|null $key
 * @property string|null $description
 * @property float|null  $value
 *
 * @package JBZoo\CIReportConverter\Formats\Metric
 */
class Metric extends AbstractNode
{
    /**
     * @var array
     */
    protected array $meta = [
        'key'         => ['string'],
        'name'        => ['string'],
        'description' => ['string'],
        'value'       => ['float'],
    ];
}
