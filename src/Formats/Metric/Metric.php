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

namespace JBZoo\CiReportConverter\Formats\Metric;

use JBZoo\CiReportConverter\Formats\AbstractNode;

/**
 * Class Metric
 *
 * @property string|null $name
 * @property string|null $key
 * @property string|null $description
 * @property float|null  $value
 *
 * @package JBZoo\CiReportConverter\Formats\Metric
 */
class Metric extends AbstractNode
{
    /**
     * @var array
     */
    protected $meta = [
        'key'         => ['string'],
        'name'        => ['string'],
        'description' => ['string'],
        'value'       => ['float'],
    ];
}
