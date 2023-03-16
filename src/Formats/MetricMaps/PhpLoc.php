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

namespace JBZoo\CIReportConverter\Formats\MetricMaps;

class PhpLoc extends AbstractMetricMap
{
    protected string $name = 'PHPloc';

    /** @var string[] */
    protected array $map = [
        'files'       => 'FileSystem / Files',
        'directories' => 'FileSystem / Directories',

        'loc'           => 'Lines',
        'lloc'          => 'Lines / Logical',
        'cloc'          => 'Lines / Comments',
        'ncloc'         => 'Lines / Non-Comment',
        'llocClasses'   => 'Lines / Classes',
        'classLlocAvg'  => 'Lines / Classes / Length / Average',
        'classLlocMin'  => 'Lines / Classes / Length / Min',
        'classLlocMax'  => 'Lines / Classes / Length / Max',
        'methodLlocAvg' => 'Lines / Methods / Length / Average ',
        'methodLlocMin' => 'Lines / Methods / Length / Min',
        'methodLlocMax' => 'Lines / Methods / Length / Max',
        'llocFunctions' => 'Lines / Functions',
        'llocByNof'     => 'Lines / Functions / Average Length',
        'llocGlobal'    => 'Lines / Outside Functions or Classes',

        'ccnByLloc'    => 'Complexity / Average',
        'ccn'          => 'Complexity / Total',
        'ccnMethods'   => 'Complexity / Total in Methods',
        'classCcnAvg'  => 'Complexity / Classes / Average',
        'classCcnMin'  => 'Complexity / Classes / Min',
        'classCcnMax'  => 'Complexity / Classes / Max',
        'methodCcnAvg' => 'Complexity / Methods / Average',
        'methodCcnMin' => 'Complexity / Methods / Min',
        'methodCcnMax' => 'Complexity / Methods / Max',

        'globalAccesses'              => 'Calls / Global',
        'globalConstantAccesses'      => 'Calls / Global / Constants',
        'globalVariableAccesses'      => 'Calls / Global / Variables',
        'superGlobalVariableAccesses' => 'Calls / Super-Global Variables',
        'attributeAccesses'           => 'Calls / Attributes',
        'instanceAttributeAccesses'   => 'Calls / Attributes / Non-Static',
        'staticAttributeAccesses'     => 'Calls / Attributes / Static',
        'methodCalls'                 => 'Calls / Methods',
        'instanceMethodCalls'         => 'Calls / Methods / Non-Static',
        'staticMethodCalls'           => 'Calls / Methods / Static Methods',

        'namespaces'         => 'Namespaces',
        'interfaces'         => 'Interfaces',
        'traits'             => 'Traits',
        'classes'            => 'Classes',
        'abstractClasses'    => 'Classes / Abstract',
        'concreteClasses'    => 'Classes / Concrete',
        'methods'            => 'Methods',
        'staticMethods'      => 'Methods / Static',
        'nonStaticMethods'   => 'Methods / Non-Static',
        'publicMethods'      => 'Methods / Public',
        'nonPublicMethods'   => 'Methods / Non-Public',
        'functions'          => 'Functions',
        'namedFunctions'     => 'Functions / Named',
        'anonymousFunctions' => 'Functions / Anonymous',
        'constants'          => 'Constants',
        'globalConstants'    => 'Constants / Global',
        'classConstants'     => 'Constants / Classes',

        // Tests
        'testClasses' => 'Tests / Classes',
        'testMethods' => 'Tests / Methods',
    ];
}
