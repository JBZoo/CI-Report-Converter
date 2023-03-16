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

class JUnit extends AbstractMetricMap
{
    protected string $name = 'JUnit';

    /** @var string[] */
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
