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
 * Class PhpDepend
 * @package JBZoo\ToolboxCI\Formats\MetricMaps
 */
class PhpDepend extends AbstractMetricMap
{
    /**
     * @var string
     */
    protected $name = 'PHPDepend';

    /**
     * @var string[]
     */
    protected $map = [
        // Size
        'loc'   => 'Lines',
        'lloc'  => 'Lines / Logical',
        'cloc'  => 'Lines / Comments',
        'eloc'  => 'Lines / Executable',
        'ncloc' => 'Lines / Non-Comment',

        'wmc'   => 'Weighted Method Count',
        'wmci'  => 'Weighted Method Count / Inherited',
        'wmcnp' => 'Weighted Method Count / Non-Private',

        'npath'  => 'Complexity / NPath',
        'ccn'    => 'Complexity / Total',
        'ccn2'   => 'Complexity / Extended',
        'cr'     => 'Complexity / Code Rank',
        'rcr'    => 'Complexity / Code Rank / Reverse',
        'ahh'    => 'Complexity / Hierarchy Height / Average',
        'dit'    => 'Complexity / Depth of Inheritance Tree',
        'maxDIT' => 'Complexity / Depth of Inheritance Tree / Max',

        'nop' => 'Namespaces',
        'noi' => 'Interfaces',
        'nof' => 'Functions',

        'noc'   => 'Classes',
        'csz'   => 'Classes / Size',
        'roots' => 'Classes / Root',
        'clsa'  => 'Classes / Abstract',
        'clsc'  => 'Classes / Concrete',
        'nocc'  => 'Classes / Child',
        'leafs' => 'Classes / Leaf',
        'andc'  => 'Classes / Average Number of Derived',
        'cis'   => 'Classes / Interface Size',

        'nom'  => 'Methods',
        'npm'  => 'Methods / Public',
        'noom' => 'Methods / Inherited',
        'noam' => 'Methods / Non-Inherited',

        'vars'   => 'Attributes',
        'varsi'  => 'Attributes / Inherited',
        'varsnp' => 'Attributes / Non-Private',

        'calls'  => 'Calls / Methods or Functions',
        'fanout' => 'Calls / Fanouts',

        'ca'  => 'Coupling / Afferent',
        'cbo' => 'Coupling / Between Objects',
        'ce'  => 'Coupling / Efferent',
    ];
}
