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
 * Class PhpMetrics
 * @package JBZoo\CiReportConverter\Formats\MetricMaps
 *
 */
class PhpMetrics extends AbstractMetricMap
{
    /**
     * @var string
     */
    protected $name = 'PHPMetrics';

    // @see http://www.phpmetrics.org/documentation/index.html
    // @see http://en.wikipedia.org/wiki/Halstead_complexity_measures
    // @see http://en.wikipedia.org/wiki/Maintainability
    // @see http://en.wikipedia.org/wiki/Cyclomatic_complexity

    /**
     * @var string[]
     */
    protected $map = [
        'distance' => 'Myers / Distance',
        'interval' => 'Myers / Interval',

        'cc'                   => 'Complexity',
        'cyclomaticComplexity' => 'Complexity',
        'dc'                   => 'Complexity / Data',
        'rdc'                  => 'Complexity / Relative Data',
        'rsc'                  => 'Complexity / Relative Structural',
        'rsysc'                => 'Complexity / Relative System',
        'sc'                   => 'Complexity / System',
        'sysc'                 => 'Complexity / Total System',

        'loc'       => 'Lines',
        'lloc'      => 'Lines / Logical',
        'noi'       => 'Lines / Interfaces',
        'noc'       => 'Lines / Classes',
        'noca'      => 'Lines / Classes / Abstract',
        'nocc'      => 'Lines / Classes / Concrete',
        'nom'       => 'Lines / Methods',
        'operators' => 'Lines / Operators',

        'maintainabilityIndex' => 'Maintainability / Index',
        'MI'                   => 'Maintainability / Index',
        'MIwC'                 => 'Maintainability / Index / Without Comments',
        'length'               => 'Maintainability / Length',
        'volume'               => 'Maintainability / Volume',
        'vocabulary'           => 'Maintainability / Used Vocabulary',
        'time'                 => 'Maintainability / Time to Understand',
        'effort'               => 'Maintainability / Effort to Understand',
        'intelligentContent'   => 'Maintainability / Intelligent Content',
        'IC'                   => 'Maintainability / Intelligent Content',
        'commw'                => 'Maintainability / Comment Weight',
        'commentWeight'        => 'Maintainability / Comment Weight',
        'bugs'                 => 'Maintainability / Estimated Bugs',
        'instability'          => 'Maintainability / Class Resilience to Change',
        'lcom'                 => 'Maintainability / Lack of Cohesion',

        'difficulty' => 'Difficulty / Index',
        'diff'       => 'Difficulty / Code',

        'ce'               => 'Coupling / Efferent',
        'efferentCoupling' => 'Coupling / Efferent',
        'ca'               => 'Coupling / Afferent',
        'afferentCoupling' => 'Coupling / Afferent',
    ];
}
