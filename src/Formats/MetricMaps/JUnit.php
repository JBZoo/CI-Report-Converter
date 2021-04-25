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

namespace JBZoo\ToolboxCI\Formats\MetricMaps;

/**
 * Class JUnit
 * @package JBZoo\ToolboxCI\Formats\MetricMaps
 */
class JUnit extends AbstractMetricMap
{
    /**
     * @var string
     */
    protected $name = 'JUnit';

    /**
     * @var string[]
     */
    protected $map = [
        'time'       => 'Tests / Time',
        'tests'      => 'Tests / Count',
        'assertions' => 'Tests / Assertions',
        'errors'     => 'Tests / Errors',
        'warnings'   => 'Tests / Warnings',
        'failure'    => 'Tests / Failures',
        'skipped'    => 'Tests / Skipped',
    ];
}
