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

/**
 * Class PhpUnitClover
 * @package JBZoo\CIReportConverter\Formats\MetricMaps
 */
class PhpUnitClover extends AbstractMetricMap
{
    /**
     * @var string
     */
    protected string $name = 'PHPUnit';

    /**
     * @var string[]
     */
    protected array $map = [
        'CodeCoverageL'           => 'Coverage / Line-level, %',
        'CodeCoverageAbsLTotal'   => 'Coverage / Found LOC',
        'CodeCoverageAbsLCovered' => 'Coverage / Covered LOC',

        'CodeCoverageB'           => 'Coverage / Block-level, %',
        'CodeCoverageAbsBTotal'   => 'Coverage / Found Blocks',
        'CodeCoverageAbsBCovered' => 'Coverage / Covered Blocks',

        'CodeCoverageM'           => 'Coverage / Method-level, %',
        'CodeCoverageAbsMTotal'   => 'Coverage / Found Methods',
        'CodeCoverageAbsMCovered' => 'Coverage / Covered Methods',

        'CodeCoverageC'           => 'Coverage / Class-level, %',
        'CodeCoverageAbsCTotal'   => 'Coverage / Found Classes',
        'CodeCoverageAbsCCovered' => 'Coverage / Covered Classes',

        'Files'                 => 'Coverage / Files',
        'LinesOfCode'           => 'Coverage / Lines',
        'NonCommentLinesOfCode' => 'Coverage / Lines Non-Comment',

        'CRAPAmount'  => 'CRAP / Amount',
        'CRAPPercent' => 'CRAP / Percent',
        'CRAPTotal'   => 'CRAP / Total',
        'CRAPAverage' => 'CRAP / Average',
        'CRAPMaximum' => 'CRAP / Maximum',
    ];
}
