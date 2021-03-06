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

namespace JBZoo\CiReportConverter\Formats\MetricMaps;

/**
 * Class JUnit
 * @package JBZoo\CiReportConverter\Formats\MetricMaps
 */
class JUnit extends AbstractMetricMap
{
    /**
     * @var string
     */
    protected string $name = 'JUnit';

    /**
     * @var string[]
     */
    protected array $map = [
        'time'       => 'Tests / Time',
        'tests'      => 'Tests / Count',
        'assertions' => 'Tests / Assertions',
        'errors'     => 'Tests / Errors',
        'warnings'   => 'Tests / Warnings',
        'failure'    => 'Tests / Failures',
        'skipped'    => 'Tests / Skipped',
    ];
}
