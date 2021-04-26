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

namespace JBZoo\PHPUnit;

use JBZoo\CiReportConverter\Converters\JUnitStatsTcConverter;
use JBZoo\CiReportConverter\Converters\PhpDependStatsTcConverter;
use JBZoo\CiReportConverter\Converters\PhpLocStatsTcConverter;
use JBZoo\CiReportConverter\Converters\PhpMetricsStatsTcConverter;
use JBZoo\CiReportConverter\Converters\PhpUnitCloverStatsTcConverter;

/**
 * Class ConverterTeamCityStatsTest
 * @package JBZoo\PHPUnit
 */
class ConverterTeamCityStatsTest extends PHPUnit
{
    public function testPhpLocJson()
    {
        $converter = (new PhpLocStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(file_get_contents(Fixtures::PHPLOC_JSON));
        $output = $converter->fromInternalMetric($sourceCode);

        isSame(implode('', [
            "\n##teamcity[buildStatisticValue key='Calls / Attributes (PHPloc:attributeAccesses)' value='8' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Calls / Attributes / Non-Static (PHPloc:instanceAttributeAccesses)' value='8' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Calls / Attributes / Static (PHPloc:staticAttributeAccesses)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Calls / Global (PHPloc:globalAccesses)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Calls / Global / Constants (PHPloc:globalConstantAccesses)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Calls / Global / Variables (PHPloc:globalVariableAccesses)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Calls / Methods (PHPloc:methodCalls)' value='50' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Calls / Methods / Non-Static (PHPloc:instanceMethodCalls)' value='47' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Calls / Methods / Static Methods (PHPloc:staticMethodCalls)' value='3' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Calls / Super-Global Variables (PHPloc:superGlobalVariableAccesses)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Classes (PHPloc:classes)' value='4' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Classes / Abstract (PHPloc:abstractClasses)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Classes / Concrete (PHPloc:concreteClasses)' value='4' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Average (PHPloc:ccnByLloc)' value='0.134615' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Classes / Average (PHPloc:classCcnAvg)' value='2.75' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Classes / Max (PHPloc:classCcnMax)' value='5' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Classes / Min (PHPloc:classCcnMin)' value='1' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Methods / Average (PHPloc:methodCcnAvg)' value='1.368421' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Methods / Max (PHPloc:methodCcnMax)' value='2' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Methods / Min (PHPloc:methodCcnMin)' value='1' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Total (PHPloc:ccn)' value='7' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Total in Methods (PHPloc:ccnMethods)' value='7' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Constants (PHPloc:constants)' value='1' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Constants / Classes (PHPloc:classConstants)' value='1' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Constants / Global (PHPloc:globalConstants)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='FileSystem / Directories (PHPloc:directories)' value='1' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='FileSystem / Files (PHPloc:files)' value='4' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Functions (PHPloc:functions)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Functions / Anonymous (PHPloc:anonymousFunctions)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Functions / Named (PHPloc:namedFunctions)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Interfaces (PHPloc:interfaces)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines (PHPloc:loc)' value='318' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Classes (PHPloc:llocClasses)' value='48' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Classes / Length / Average (PHPloc:classLlocAvg)' value='12' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Classes / Length / Max (PHPloc:classLlocMax)' value='22' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Classes / Length / Min (PHPloc:classLlocMin)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Comments (PHPloc:cloc)' value='133' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Functions (PHPloc:llocFunctions)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Functions / Average Length (PHPloc:llocByNof)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Logical (PHPloc:lloc)' value='52' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Methods / Length / Average  (PHPloc:methodLlocAvg)' value='2.421053' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Methods / Length / Max (PHPloc:methodLlocMax)' value='7' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Methods / Length / Min (PHPloc:methodLlocMin)' value='1' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Non-Comment (PHPloc:ncloc)' value='185' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Outside Functions or Classes (PHPloc:llocGlobal)' value='4' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Methods (PHPloc:methods)' value='19' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Methods / Non-Public (PHPloc:nonPublicMethods)' value='1' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Methods / Non-Static (PHPloc:nonStaticMethods)' value='19' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Methods / Public (PHPloc:publicMethods)' value='18' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Methods / Static (PHPloc:staticMethods)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Namespaces (PHPloc:namespaces)' value='2' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='PHPloc:undefined' value='42' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Tests / Classes (PHPloc:testClasses)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Tests / Methods (PHPloc:testMethods)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Traits (PHPloc:traits)' value='0' flowId='1']\n",
        ]), $output);
    }

    public function testPhpDependXml()
    {
        $converter = (new PhpDependStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(file_get_contents(Fixtures::PHP_DEPEND_XML));
        $output = $converter->fromInternalMetric($sourceCode);

        isSame(implode('', [
            "\n##teamcity[buildStatisticValue key='Calls / Fanouts (PHPDepend:fanout)' value='42' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Calls / Methods or Functions (PHPDepend:calls)' value='167' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Classes (PHPDepend:noc)' value='16' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Classes / Abstract (PHPDepend:clsa)' value='3' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Classes / Average Number of Derived (PHPDepend:andc)' value='0.631579' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Classes / Concrete (PHPDepend:clsc)' value='13' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Classes / Leaf (PHPDepend:leafs)' value='8' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Classes / Root (PHPDepend:roots)' value='4' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Depth of Inheritance Tree / Max (PHPDepend:maxDIT)' value='4' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Extended (PHPDepend:ccn2)' value='130' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Hierarchy Height / Average (PHPDepend:ahh)' value='2.5' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Total (PHPDepend:ccn)' value='110' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Functions (PHPDepend:nof)' value='5' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Interfaces (PHPDepend:noi)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines (PHPDepend:loc)' value='1553' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Comments (PHPDepend:cloc)' value='630' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Executable (PHPDepend:eloc)' value='688' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Logical (PHPDepend:lloc)' value='429' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Non-Comment (PHPDepend:ncloc)' value='923' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Methods (PHPDepend:nom)' value='51' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Namespaces (PHPDepend:nop)' value='10' flowId='1']\n",
        ]), $output);
    }

    public function testPhpMetricsXml()
    {
        $converter = (new PhpMetricsStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(file_get_contents(Fixtures::PHP_METRICS_XML));
        $output = $converter->fromInternalMetric($sourceCode);

        isSame(implode('', [
            "\n##teamcity[buildStatisticValue key='Complexity (PHPMetrics:cyclomaticComplexity)' value='4.76' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Data (PHPMetrics:dc)' value='2' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Relative Data (PHPMetrics:rdc)' value='0.41' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Relative Structural (PHPMetrics:rsc)' value='0.68' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Relative System (PHPMetrics:rsysc)' value='1.09' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / System (PHPMetrics:sc)' value='4.08' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Complexity / Total System (PHPMetrics:sysc)' value='6.08' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coupling / Afferent (PHPMetrics:afferentCoupling)' value='0.24' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coupling / Efferent (PHPMetrics:efferentCoupling)' value='1.48' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Difficulty / Index (PHPMetrics:difficulty)' value='8.15' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines (PHPMetrics:loc)' value='1790' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Classes (PHPMetrics:noc)' value='16' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Classes / Abstract (PHPMetrics:noca)' value='3' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Classes / Concrete (PHPMetrics:nocc)' value='13' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Interfaces (PHPMetrics:noi)' value='0' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Logical (PHPMetrics:lloc)' value='363' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Lines / Methods (PHPMetrics:nom)' value='55' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Maintainability / Class Resilience to Change (PHPMetrics:instability)' value='0.34' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Maintainability / Comment Weight (PHPMetrics:commentWeight)' value='43.64' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Maintainability / Effort to Understand (PHPMetrics:effort)' value='15348.57' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Maintainability / Estimated Bugs (PHPMetrics:bugs)' value='0.22' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Maintainability / Index (PHPMetrics:maintainabilityIndex)' value='117.48' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Maintainability / Intelligent Content (PHPMetrics:intelligentContent)' value='43.46' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Maintainability / Lack of Cohesion (PHPMetrics:lcom)' value='1.04' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Maintainability / Length (PHPMetrics:length)' value='109.52' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Maintainability / Time to Understand (PHPMetrics:time)' value='852.64' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Maintainability / Used Vocabulary (PHPMetrics:vocabulary)' value='27.28' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Maintainability / Volume (PHPMetrics:volume)' value='660.36' flowId='1']\n",
        ]), $output);
    }

    public function testPhpUnitCloverXml()
    {
        $converter = (new PhpUnitCloverStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(file_get_contents(Fixtures::PHPUNIT_CLOVER));
        $output = $converter->fromInternalMetric($sourceCode);

        isSame(implode('', [
            "\n##teamcity[buildStatisticValue key='CRAP / Amount (PHPUnit:CRAPAmount)' value='147' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='CRAP / Average (PHPUnit:CRAPAverage)' value='3.829524' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='CRAP / Maximum (PHPUnit:CRAPMaximum)' value='20' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='CRAP / Percent (PHPUnit:CRAPPercent)' value='100' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='CRAP / Total (PHPUnit:CRAPTotal)' value='562.94' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Block-level, % (PHPUnit:CodeCoverageB)' value='89.776358' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Class-level, % (PHPUnit:CodeCoverageC)' value='76.470588' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Covered Blocks (PHPUnit:CodeCoverageAbsBCovered)' value='843' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Covered Classes (PHPUnit:CodeCoverageAbsCCovered)' value='26' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Covered LOC (PHPUnit:CodeCoverageAbsLCovered)' value='948' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Covered Methods (PHPUnit:CodeCoverageAbsMCovered)' value='105' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Files (PHPUnit:Files)' value='51' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Found Blocks (PHPUnit:CodeCoverageAbsBTotal)' value='939' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Found Classes (PHPUnit:CodeCoverageAbsCTotal)' value='34' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Found LOC (PHPUnit:CodeCoverageAbsLTotal)' value='1086' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Found Methods (PHPUnit:CodeCoverageAbsMTotal)' value='147' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Line-level, % (PHPUnit:CodeCoverageL)' value='87.292818' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Lines (PHPUnit:LinesOfCode)' value='4542' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Lines Non-Comment (PHPUnit:NonCommentLinesOfCode)' value='2869' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Coverage / Method-level, % (PHPUnit:CodeCoverageM)' value='71.428571' flowId='1']\n",
        ]), $output);
    }

    public function testJUnitXml()
    {
        $converter = (new JUnitStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(file_get_contents(Fixtures::PHPUNIT_JUNIT_NESTED));
        $output = $converter->fromInternalMetric($sourceCode);

        isSame(implode('', [
            "\n##teamcity[buildStatisticValue key='Tests / Assertions (JUnit:assertions)' value='8' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Tests / Count (JUnit:tests)' value='14' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Tests / Errors (JUnit:errors)' value='3' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Tests / Failures (JUnit:failure)' value='4' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Tests / Skipped (JUnit:skipped)' value='2' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Tests / Time (JUnit:time)' value='10.51172' flowId='1']\n",
            "\n##teamcity[buildStatisticValue key='Tests / Warnings (JUnit:warnings)' value='1' flowId='1']\n",
        ]), $output);
    }
}
