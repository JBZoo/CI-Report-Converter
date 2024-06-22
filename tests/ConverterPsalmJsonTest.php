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

namespace JBZoo\PHPUnit;

use JBZoo\CIReportConverter\Converters\JUnitConverter;
use JBZoo\CIReportConverter\Converters\PsalmJsonConverter;
use JBZoo\CIReportConverter\Converters\TeamCityTestsConverter;

final class ConverterPsalmJsonTest extends PHPUnit
{
    public function testConvertToInternal(): void
    {
        $actual = (new PsalmJsonConverter())
            ->toInternal(\file_get_contents(Fixtures::PSALM_JSON));

        isSame([
            '_node'    => 'SourceSuite',
            'name'     => 'Psalm',
            'tests'    => 3,
            'warnings' => 2,
            'failure'  => 1,
        ], $actual->toArray()['data']);

        isSame([
            '_node'     => 'SourceCase',
            'name'      => 'src/JUnit/TestCaseElement.php line 34',
            'class'     => 'MissingReturnType',
            'classname' => 'MissingReturnType',
            'file'      => '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElement.php',
            'line'      => 34,
            'failure'   => [
                'type'    => 'MissingReturnType',
                'message' => 'Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setName does not have a return type, expecting void',
                'details' => '
Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setName does not have a return type, expecting void
Rule       : MissingReturnType
File Path  : /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElement.php:34
Snippet    : `public function setName()`
Docs       : https://psalm.dev/050
Severity   : error
Error Level: 2
',
            ],
        ], $actual->toArray()['suites'][0]['cases'][0]);
    }

    public function testConvertToJUnit(): void
    {
        $actual = (new PsalmJsonConverter())
            ->toInternal(\file_get_contents(Fixtures::PSALM_JSON));

        $junit = (new JUnitConverter())->fromInternal($actual);

        isSame(\implode("\n", [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<testsuites>',
            '  <testsuite name="Psalm" tests="3" warnings="2" failures="1">',
            '    <testsuite name="src/JUnit/TestCaseElement.php" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElement.php" tests="2" warnings="1" failures="1">',
            '      <testcase name="src/JUnit/TestCaseElement.php line 34" class="MissingReturnType" classname="MissingReturnType" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElement.php" line="34">',
            '        <failure type="MissingReturnType" message="Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setName does not have a return type, expecting void">',
            'Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setName does not have a return type, expecting void',
            'Rule       : MissingReturnType',
            'File Path  : /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElement.php:34',
            'Snippet    : `public function setName()`',
            'Docs       : https://psalm.dev/050',
            'Severity   : error',
            'Error Level: 2',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/TestCaseElement.php line 42" class="MissingReturnType" classname="MissingReturnType" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElement.php" line="42">',
            '        <warning type="MissingReturnType" message="Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setClassname does not have a return type, expecting void">',
            'Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setClassname does not have a return type, expecting void',
            'Rule       : MissingReturnType',
            'File Path  : /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElement.php:42',
            'Snippet    : `public function setClassname()`',
            'Docs       : https://psalm.dev/050',
            'Severity   : info',
            'Error Level: -1',
            '</warning>',
            '      </testcase>',
            '    </testsuite>',
            '    <testsuite name="src/JUnit/TestCaseElementSuppress.php" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElementSuppress.php" tests="1" warnings="1">',
            '      <testcase name="src/JUnit/TestCaseElementSuppress.php line 42" class="MissingReturnType" classname="MissingReturnType" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElementSuppress.php" line="42">',
            '        <warning type="MissingReturnType" message="Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setClassname does not have a return type, expecting void">',
            'Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setClassname does not have a return type, expecting void',
            'Rule       : MissingReturnType',
            'File Path  : /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElementSuppress.php:42',
            'Snippet    : `public function setClassname()`',
            'Docs       : https://psalm.dev/050',
            'Severity   : suppress',
            'Error Level: -2',
            '</warning>',
            '      </testcase>',
            '    </testsuite>',
            '  </testsuite>',
            '</testsuites>',
            '',
        ]), $junit);
    }

    public function testConvertToTeamCity(): void
    {
        $actual = (new PsalmJsonConverter())
            ->toInternal(\file_get_contents(Fixtures::PSALM_JSON));

        $junit = (new TeamCityTestsConverter(['show-datetime' => false], 76978))->fromInternal($actual);

        isSame(\implode('', [
            "\n##teamcity[testCount count='3' flowId='76978']\n",
            "\n##teamcity[testSuiteStarted name='Psalm' flowId='76978']\n",
            "\n##teamcity[testSuiteStarted name='src/JUnit/TestCaseElement.php' locationHint='php_qn:///Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElement.php::\\src/JUnit/TestCaseElement.php' flowId='76978']\n",
            "\n##teamcity[testStarted name='src/JUnit/TestCaseElement.php line 34' locationHint='php_qn:///Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElement.php::\\MissingReturnType::src/JUnit/TestCaseElement.php line 34' flowId='76978']\n",
            "\n##teamcity[testFailed name='src/JUnit/TestCaseElement.php line 34' message='Method JBZoo\\CIReportConverter\\JUnit\\TestCaseElement::setName does not have a return type, expecting void' details=' Rule       : MissingReturnType|n File Path  : /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElement.php:34|n Snippet    : `public function setName()`|n Docs       : https://psalm.dev/050|n Severity   : error|n Error Level: 2|n ' flowId='76978']\n",
            "\n##teamcity[testFinished name='src/JUnit/TestCaseElement.php line 34' flowId='76978']\n",
            "\n##teamcity[testStarted name='src/JUnit/TestCaseElement.php line 42' locationHint='php_qn:///Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElement.php::\\MissingReturnType::src/JUnit/TestCaseElement.php line 42' flowId='76978']\n",
            "\n##teamcity[testFailed name='src/JUnit/TestCaseElement.php line 42' message='Method JBZoo\\CIReportConverter\\JUnit\\TestCaseElement::setClassname does not have a return type, expecting void' details=' Rule       : MissingReturnType|n File Path  : /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElement.php:42|n Snippet    : `public function setClassname()`|n Docs       : https://psalm.dev/050|n Severity   : info|n Error Level: -1|n ' flowId='76978']\n",
            "\n##teamcity[testFinished name='src/JUnit/TestCaseElement.php line 42' flowId='76978']\n",
            "\n##teamcity[testSuiteFinished name='src/JUnit/TestCaseElement.php' flowId='76978']\n",
            "\n##teamcity[testSuiteStarted name='src/JUnit/TestCaseElementSuppress.php' locationHint='php_qn:///Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElementSuppress.php::\\src/JUnit/TestCaseElementSuppress.php' flowId='76978']\n",
            "\n##teamcity[testStarted name='src/JUnit/TestCaseElementSuppress.php line 42' locationHint='php_qn:///Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElementSuppress.php::\\MissingReturnType::src/JUnit/TestCaseElementSuppress.php line 42' flowId='76978']\n",
            "\n##teamcity[testFailed name='src/JUnit/TestCaseElementSuppress.php line 42' message='Method JBZoo\\CIReportConverter\\JUnit\\TestCaseElement::setClassname does not have a return type, expecting void' details=' Rule       : MissingReturnType|n File Path  : /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/src/JUnit/TestCaseElementSuppress.php:42|n Snippet    : `public function setClassname()`|n Docs       : https://psalm.dev/050|n Severity   : suppress|n Error Level: -2|n ' flowId='76978']\n",
            "\n##teamcity[testFinished name='src/JUnit/TestCaseElementSuppress.php line 42' flowId='76978']\n",
            "\n##teamcity[testSuiteFinished name='src/JUnit/TestCaseElementSuppress.php' flowId='76978']\n",
            "\n##teamcity[testSuiteFinished name='Psalm' flowId='76978']\n",
            '',
        ]), $junit);
    }
}
