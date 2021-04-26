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

use JBZoo\CiReportConverter\Converters\CheckStyleConverter;
use JBZoo\CiReportConverter\Converters\JUnitConverter;
use JBZoo\CiReportConverter\Converters\TeamCityInspectionsConverter;

/**
 * Class ConverterTeamCityInspectionsTest
 *
 * @package JBZoo\PHPUnit
 */
class ConverterTeamCityInspectionsTest extends PHPUnit
{
    public function testPhpCsCodestyle()
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';
        $source = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(file_get_contents(Fixtures::PHPCS_CODESTYLE));

        $actual = (new TeamCityInspectionsConverter(['show-datetime' => false]))
            ->setFlowId(1)
            ->fromInternal($source);

        isSame(
            "\n" . implode("\n\n", [
                "##teamcity[inspectionType id='CheckStyle:PSR12.Properties.ConstantVisibility.NotFound' name='PSR12.Properties.ConstantVisibility.NotFound' category='CheckStyle' description='Issues found while checking coding standards' flowId='1']",
                "##teamcity[inspection typeId='CheckStyle:PSR12.Properties.ConstantVisibility.NotFound' file='src/JUnit/JUnitXml.php' line='24' message='------------------------------------------------------------------------------------------------------------------------|nsrc/JUnit/JUnitXml.php line 24, column 5|nVisibility must be declared on all constants if your project supports PHP 7.1 or later|n Rule     : PSR12.Properties.ConstantVisibility.NotFound|n File Path: src/JUnit/JUnitXml.php:24:5|n Severity : warning' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspectionType id='CheckStyle:Squiz.Functions.MultiLineFunctionDeclaration.BraceOnSameLine' name='Squiz.Functions.MultiLineFunctionDeclaration.BraceOnSameLine' category='CheckStyle' description='Issues found while checking coding standards' flowId='1']",
                "##teamcity[inspection typeId='CheckStyle:Squiz.Functions.MultiLineFunctionDeclaration.BraceOnSameLine' file='src/JUnit/JUnitXml.php' line='44' message='------------------------------------------------------------------------------------------------------------------------|nsrc/JUnit/JUnitXml.php line 44, column 35|nOpening brace should be on a new line|n Rule     : Squiz.Functions.MultiLineFunctionDeclaration.BraceOnSameLine|n File Path: src/JUnit/JUnitXml.php:44:35|n Severity : error' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspectionType id='CheckStyle:PSR2.Files.EndFileNewline.NoneFound' name='PSR2.Files.EndFileNewline.NoneFound' category='CheckStyle' description='Issues found while checking coding standards' flowId='1']",
                "##teamcity[inspection typeId='CheckStyle:PSR2.Files.EndFileNewline.NoneFound' file='src/JUnit/JUnitXml.php' line='50' message='------------------------------------------------------------------------------------------------------------------------|nsrc/JUnit/JUnitXml.php line 50, column 1|nExpected 1 newline at end of file; 0 found|n Rule     : PSR2.Files.EndFileNewline.NoneFound|n File Path: src/JUnit/JUnitXml.php:50|n Severity : error' SEVERITY='ERROR' flowId='1']",
            ]) . "\n",
            $actual
        );
    }

    public function testPhanCodeStyle()
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';
        $source = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(file_get_contents(Fixtures::PHAN_CHECKSTYLE));

        $actual = (new TeamCityInspectionsConverter(['show-datetime' => false]))
            ->setFlowId(1)
            ->fromInternal($source);

        isSame(
            "\n" . implode("\n\n", [
                "##teamcity[inspectionType id='CheckStyle:PhanPossiblyFalseTypeMismatchProperty' name='PhanPossiblyFalseTypeMismatchProperty' category='CheckStyle' description='Issues found while checking coding standards' flowId='1']",
                "##teamcity[inspection typeId='CheckStyle:PhanPossiblyFalseTypeMismatchProperty' file='src/JUnit/JUnitXml.php' line='37' message='------------------------------------------------------------------------------------------------------------------------|nsrc/JUnit/JUnitXml.php line 37|nAssigning \$this->rootElement of type \DOMElement||false to property but \JBZoo\CiReportConverter\JUnit\JUnitXml->rootElement is \DOMElement (false is incompatible)|n Rule     : PhanPossiblyFalseTypeMismatchProperty|n File Path: src/JUnit/JUnitXml.php:37|n Severity : warning' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspectionType id='CheckStyle:PhanPluginCanUseReturnType' name='PhanPluginCanUseReturnType' category='CheckStyle' description='Issues found while checking coding standards' flowId='1']",
                "##teamcity[inspection typeId='CheckStyle:PhanPluginCanUseReturnType' file='src/JUnit/JUnitXml.php' line='44' message='------------------------------------------------------------------------------------------------------------------------|nsrc/JUnit/JUnitXml.php line 44|nCan use \JBZoo\CiReportConverter\JUnit\TestSuiteElement as a return type of addTestSuite|n Rule     : PhanPluginCanUseReturnType|n File Path: src/JUnit/JUnitXml.php:44|n Severity : warning' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspectionType id='CheckStyle:PhanPluginCanUseParamType' name='PhanPluginCanUseParamType' category='CheckStyle' description='Issues found while checking coding standards' flowId='1']",
                "##teamcity[inspection typeId='CheckStyle:PhanPluginCanUseParamType' file='src/JUnit/TestCaseElement.php' line='34' message='------------------------------------------------------------------------------------------------------------------------|nsrc/JUnit/TestCaseElement.php line 34|nCan use string as the type of parameter \$name of setName|n Rule     : PhanPluginCanUseParamType|n File Path: src/JUnit/TestCaseElement.php:34|n Severity : warning' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspectionType id='CheckStyle:PhanPluginSuspiciousParamPositionInternal' name='PhanPluginSuspiciousParamPositionInternal' category='CheckStyle' description='Issues found while checking coding standards' flowId='1']",
                "##teamcity[inspection typeId='CheckStyle:PhanPluginSuspiciousParamPositionInternal' file='src/JUnit/TestCaseElement.php' line='36' message='------------------------------------------------------------------------------------------------------------------------|nsrc/JUnit/TestCaseElement.php line 36|nSuspicious order for argument name - This is getting passed to parameter #1 (string \$name) of \JBZoo\CiReportConverter\JUnit\TestCaseElement::setAttribute(string \$name, string \$value)|n Rule     : PhanPluginSuspiciousParamPositionInternal|n File Path: src/JUnit/TestCaseElement.php:36|n Severity : warning' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CheckStyle:PhanPluginCanUseParamType' file='src/JUnit/TestCaseElement.php' line='42' message='------------------------------------------------------------------------------------------------------------------------|nsrc/JUnit/TestCaseElement.php line 42|nCan use string as the type of parameter \$classname of setClassname|n Rule     : PhanPluginCanUseParamType|n File Path: src/JUnit/TestCaseElement.php:42|n Severity : warning' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CheckStyle:PhanPluginCanUseParamType' file='src/JUnit/TestSuiteElement.php' line='35' message='------------------------------------------------------------------------------------------------------------------------|nsrc/JUnit/TestSuiteElement.php line 35|nCan use string as the type of parameter \$name of setName|n Rule     : PhanPluginCanUseParamType|n File Path: src/JUnit/TestSuiteElement.php:35|n Severity : warning' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CheckStyle:PhanPluginSuspiciousParamPositionInternal' file='src/JUnit/TestSuiteElement.php' line='37' message='------------------------------------------------------------------------------------------------------------------------|nsrc/JUnit/TestSuiteElement.php line 37|nSuspicious order for argument name - This is getting passed to parameter #1 (string \$name) of \JBZoo\CiReportConverter\JUnit\TestSuiteElement::setAttribute(string \$name, string \$value)|n Rule     : PhanPluginSuspiciousParamPositionInternal|n File Path: src/JUnit/TestSuiteElement.php:37|n Severity : warning' SEVERITY='ERROR' flowId='1']",
            ]) . "\n",
            $actual
        );
    }

    public function testJUnitSimple()
    {
        $source = (new JUnitConverter())
            ->setRootPath('/Users/smetdenis/Work/projects/jbzoo-ci-report-converter')
            ->toInternal(file_get_contents(Fixtures::PHPUNIT_JUNIT_SIMPLE));

        $actual = (new TeamCityInspectionsConverter(['show-datetime' => false]))
            ->setFlowId(1)
            ->fromInternal($source);

        isSame(
            "\n" . implode("\n\n", [
                "##teamcity[inspectionType id='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' name='JBZoo\PHPUnit\ExampleTest' category='CodingStandardIssues' description='Issues found while checking coding standards' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='33' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testInValid|nFailed asserting that false is true.|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/jbzoo/phpunit/src/functions/aliases.php:107|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php:35' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='38' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testSkipped' SEVERITY='WEAK WARNING' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='43' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testIncomplete' SEVERITY='WEAK WARNING' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='48' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testFail|nSome reason to fail|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/jbzoo/phpunit/src/functions/aliases.php:51|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php:50' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='71' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testNoAssert' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='75' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testNotice|nUndefined variable: aaa|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php:77' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='80' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testWarning|nSome warning|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php:82' SEVERITY='WARNING' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='85' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest-2 / testException|nJBZoo\PHPUnit\Exception: Exception message|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php:88' SEVERITY='ERROR' flowId='1']",
            ]) . "\n",
            $actual
        );
    }

    public function testJUnitNested()
    {
        $source = (new JUnitConverter())
            ->setRootPath('/Users/smetdenis/Work/projects/jbzoo-ci-report-converter')
            ->toInternal(file_get_contents(Fixtures::PHPUNIT_JUNIT_NESTED));

        $actual = (new TeamCityInspectionsConverter(['show-datetime' => false]))
            ->setFlowId(1)
            ->fromInternal($source);

        isSame(
            "\n" . implode("\n\n", [
                "##teamcity[inspectionType id='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' name='JBZoo\PHPUnit\ExampleTest' category='CodingStandardIssues' description='Issues found while checking coding standards' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='38' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testInValid|nFailed asserting that false is true.|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/jbzoo/phpunit/src/functions/aliases.php:107|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php:40' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='43' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testSkipped' SEVERITY='WEAK WARNING' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='48' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testIncomplete' SEVERITY='WEAK WARNING' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='53' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testFail|nSome reason to fail|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/jbzoo/phpunit/src/functions/aliases.php:51|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php:55' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='76' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testNoAssert' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='80' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testNotice|nUndefined variable: aaa|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php:82' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='85' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testWarning|nSome warning|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php:87' SEVERITY='WARNING' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='90' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testException|nJBZoo\PHPUnit\Exception: Exception message|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php:93' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='96' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testCompareArrays|nFailed asserting that two arrays are identical.|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/jbzoo/phpunit/src/functions/aliases.php:197|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php:98' SEVERITY='ERROR' flowId='1']",
                "##teamcity[inspection typeId='CodingStandardIssues:JBZoo\PHPUnit\ExampleTest' file='/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php' line='101' message='------------------------------------------------------------------------------------------------------------------------|nJBZoo\PHPUnit\ExampleTest / testCompareString|nFailed asserting that two strings are identical.|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/jbzoo/phpunit/src/functions/aliases.php:197|n /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php:103' SEVERITY='ERROR' flowId='1']",
            ]) . "\n",
            $actual
        );
    }
}
