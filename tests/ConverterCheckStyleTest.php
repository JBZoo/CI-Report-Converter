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

use JBZoo\CIReportConverter\Converters\CheckStyleConverter;
use JBZoo\CIReportConverter\Converters\JUnitConverter;

final class ConverterCheckStyleTest extends PHPUnit
{
    public function testToInternalPhan(): void
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';
        $source     = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(\file_get_contents(Fixtures::PHAN_CHECKSTYLE));

        $actual = (new JUnitConverter())->fromInternal($source);
        Aliases::isValidXml($actual);

        isSame([
            '_node'     => 'SourceCase',
            'name'      => 'src/JUnit/JUnitXml.php line 37',
            'class'     => 'PhanPossiblyFalseTypeMismatchProperty',
            'classname' => 'PhanPossiblyFalseTypeMismatchProperty',
            'file'      => 'src/JUnit/JUnitXml.php',
            'line'      => 37,
            'failure'   => [
                'type'    => 'PhanPossiblyFalseTypeMismatchProperty',
                'message' => 'Assigning $this-&gt;rootElement of type \DOMElement|false to property but \JBZoo\CIReportConverter\JUnit\JUnitXml-&gt;rootElement is \DOMElement (false is incompatible)',
                'details' => \implode("\n", [
                    '',
                    'Assigning $this->rootElement of type \DOMElement|false to property but \JBZoo\CIReportConverter\JUnit\JUnitXml->rootElement is \DOMElement (false is incompatible)',
                    'Rule     : PhanPossiblyFalseTypeMismatchProperty',
                    'File Path: src/JUnit/JUnitXml.php:37',
                    'Severity : warning',
                    '',
                ]),
            ],
        ], $source->toArray()['suites'][0]['cases'][0]);

        isSame([
            '_node'   => 'SourceSuite',
            'name'    => 'CheckStyle',
            'tests'   => 7,
            'failure' => 7,
        ], $source->toArray()['data']);

        isSame(\implode("\n", [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<testsuites>',
            '  <testsuite name="CheckStyle" tests="7" failures="7">',
            '    <testsuite name="src/JUnit/JUnitXml.php" file="src/JUnit/JUnitXml.php" tests="2" failures="2">',
            '      <testcase name="src/JUnit/JUnitXml.php line 37" class="PhanPossiblyFalseTypeMismatchProperty" classname="PhanPossiblyFalseTypeMismatchProperty" file="src/JUnit/JUnitXml.php" line="37">',
            '        <failure type="PhanPossiblyFalseTypeMismatchProperty" message="Assigning $this-&amp;gt;rootElement of type \DOMElement|false to property but \JBZoo\CIReportConverter\JUnit\JUnitXml-&amp;gt;rootElement is \DOMElement (false is incompatible)">',
            'Assigning $this-&gt;rootElement of type \DOMElement|false to property but \JBZoo\CIReportConverter\JUnit\JUnitXml-&gt;rootElement is \DOMElement (false is incompatible)',
            'Rule     : PhanPossiblyFalseTypeMismatchProperty',
            'File Path: src/JUnit/JUnitXml.php:37',
            'Severity : warning',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/JUnitXml.php line 44" class="PhanPluginCanUseReturnType" classname="PhanPluginCanUseReturnType" file="src/JUnit/JUnitXml.php" line="44">',
            '        <failure type="PhanPluginCanUseReturnType" message="Can use \JBZoo\CIReportConverter\JUnit\TestSuiteElement as a return type of addTestSuite">',
            'Can use \JBZoo\CIReportConverter\JUnit\TestSuiteElement as a return type of addTestSuite',
            'Rule     : PhanPluginCanUseReturnType',
            'File Path: src/JUnit/JUnitXml.php:44',
            'Severity : warning',
            '</failure>',
            '      </testcase>',
            '    </testsuite>',
            '    <testsuite name="src/JUnit/TestCaseElement.php" file="src/JUnit/TestCaseElement.php" tests="3" failures="3">',
            '      <testcase name="src/JUnit/TestCaseElement.php line 34" class="PhanPluginCanUseParamType" classname="PhanPluginCanUseParamType" file="src/JUnit/TestCaseElement.php" line="34">',
            '        <failure type="PhanPluginCanUseParamType" message="Can use string as the type of parameter $name of setName">',
            'Can use string as the type of parameter $name of setName',
            'Rule     : PhanPluginCanUseParamType',
            'File Path: src/JUnit/TestCaseElement.php:34',
            'Severity : warning',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/TestCaseElement.php line 36" class="PhanPluginSuspiciousParamPositionInternal" classname="PhanPluginSuspiciousParamPositionInternal" file="src/JUnit/TestCaseElement.php" line="36">',
            '        <failure type="PhanPluginSuspiciousParamPositionInternal" message="Suspicious order for argument name - This is getting passed to parameter #1 (string $name) of \JBZoo\CIReportConverter\JUnit\TestCaseElement::setAttribute(string $name, string $value)">',
            'Suspicious order for argument name - This is getting passed to parameter #1 (string $name) of \JBZoo\CIReportConverter\JUnit\TestCaseElement::setAttribute(string $name, string $value)',
            'Rule     : PhanPluginSuspiciousParamPositionInternal',
            'File Path: src/JUnit/TestCaseElement.php:36',
            'Severity : warning',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/TestCaseElement.php line 42" class="PhanPluginCanUseParamType" classname="PhanPluginCanUseParamType" file="src/JUnit/TestCaseElement.php" line="42">',
            '        <failure type="PhanPluginCanUseParamType" message="Can use string as the type of parameter $classname of setClassname">',
            'Can use string as the type of parameter $classname of setClassname',
            'Rule     : PhanPluginCanUseParamType',
            'File Path: src/JUnit/TestCaseElement.php:42',
            'Severity : warning',
            '</failure>',
            '      </testcase>',
            '    </testsuite>',
            '    <testsuite name="src/JUnit/TestSuiteElement.php" file="src/JUnit/TestSuiteElement.php" tests="2" failures="2">',
            '      <testcase name="src/JUnit/TestSuiteElement.php line 35" class="PhanPluginCanUseParamType" classname="PhanPluginCanUseParamType" file="src/JUnit/TestSuiteElement.php" line="35">',
            '        <failure type="PhanPluginCanUseParamType" message="Can use string as the type of parameter $name of setName">',
            'Can use string as the type of parameter $name of setName',
            'Rule     : PhanPluginCanUseParamType',
            'File Path: src/JUnit/TestSuiteElement.php:35',
            'Severity : warning',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/TestSuiteElement.php line 37" class="PhanPluginSuspiciousParamPositionInternal" classname="PhanPluginSuspiciousParamPositionInternal" file="src/JUnit/TestSuiteElement.php" line="37">',
            '        <failure type="PhanPluginSuspiciousParamPositionInternal" message="Suspicious order for argument name - This is getting passed to parameter #1 (string $name) of \JBZoo\CIReportConverter\JUnit\TestSuiteElement::setAttribute(string $name, string $value)">',
            'Suspicious order for argument name - This is getting passed to parameter #1 (string $name) of \JBZoo\CIReportConverter\JUnit\TestSuiteElement::setAttribute(string $name, string $value)',
            'Rule     : PhanPluginSuspiciousParamPositionInternal',
            'File Path: src/JUnit/TestSuiteElement.php:37',
            'Severity : warning',
            '</failure>',
            '      </testcase>',
            '    </testsuite>',
            '  </testsuite>',
            '</testsuites>',
            '',
        ]), $actual);
    }

    public function testToInternalPHPcs(): void
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';
        $source     = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(\file_get_contents(Fixtures::PHPCS_CODESTYLE));

        $actual = (new JUnitConverter())->fromInternal($source);
        Aliases::isValidXml($actual);

        isSame(\implode("\n", [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<testsuites>',
            '  <testsuite name="CheckStyle" tests="3" failures="3">',
            '    <testsuite name="src/JUnit/JUnitXml.php" file="src/JUnit/JUnitXml.php" tests="3" failures="3">',
            '      <testcase name="src/JUnit/JUnitXml.php line 24, column 5" class="PSR12.Properties.ConstantVisibility.NotFound" classname="PSR12.Properties.ConstantVisibility.NotFound" file="src/JUnit/JUnitXml.php" line="24">',
            '        <failure type="PSR12.Properties.ConstantVisibility.NotFound" message="Visibility must be declared on all constants if your project supports PHP 7.1 or later">',
            'Visibility must be declared on all constants if your project supports PHP 7.1 or later',
            'Rule     : PSR12.Properties.ConstantVisibility.NotFound',
            'File Path: src/JUnit/JUnitXml.php:24:5',
            'Severity : warning',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/JUnitXml.php line 44, column 35" class="Squiz.Functions.MultiLineFunctionDeclaration.BraceOnSameLine" classname="Squiz.Functions.MultiLineFunctionDeclaration.BraceOnSameLine" file="src/JUnit/JUnitXml.php" line="44">',
            '        <failure type="Squiz.Functions.MultiLineFunctionDeclaration.BraceOnSameLine" message="Opening brace should be on a new line">',
            'Opening brace should be on a new line',
            'Rule     : Squiz.Functions.MultiLineFunctionDeclaration.BraceOnSameLine',
            'File Path: src/JUnit/JUnitXml.php:44:35',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/JUnitXml.php line 50, column 1" class="PSR2.Files.EndFileNewline.NoneFound" classname="PSR2.Files.EndFileNewline.NoneFound" file="src/JUnit/JUnitXml.php" line="50">',
            '        <failure type="PSR2.Files.EndFileNewline.NoneFound" message="Expected 1 newline at end of file; 0 found">',
            'Expected 1 newline at end of file; 0 found',
            'Rule     : PSR2.Files.EndFileNewline.NoneFound',
            'File Path: src/JUnit/JUnitXml.php:50',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '    </testsuite>',
            '  </testsuite>',
            '</testsuites>',
            '',
        ]), $actual);
    }

    public function testToInternalPhpStan(): void
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';
        $source     = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(\file_get_contents(Fixtures::PHPSTAN_CHECKSTYLE));

        $actual = (new JUnitConverter())->fromInternal($source);

        Aliases::isValidXml($actual);

        isSame(\implode("\n", [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<testsuites>',
            '  <testsuite name="CheckStyle" tests="5" failures="5">',
            '    <testsuite name="src/JUnit/TestCaseElement.php" file="src/JUnit/TestCaseElement.php" tests="4" failures="4">',
            '      <testcase name="src/JUnit/TestCaseElement.php line 34, column 1" class="ERROR" classname="ERROR" file="src/JUnit/TestCaseElement.php" line="34">',
            '        <failure type="ERROR" message="Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setName() has no return typehint specified.">',
            'Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setName() has no return typehint specified.',
            'File Path: src/JUnit/TestCaseElement.php:34',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/TestCaseElement.php line 42, column 1" class="ERROR" classname="ERROR" file="src/JUnit/TestCaseElement.php" line="42">',
            '        <failure type="ERROR" message="Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setClassname() has no return typehint specified.">',
            'Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setClassname() has no return typehint specified.',
            'File Path: src/JUnit/TestCaseElement.php:42',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/TestCaseElement.php line 52, column 1" class="ERROR" classname="ERROR" file="src/JUnit/TestCaseElement.php" line="52">',
            '        <failure type="ERROR" message="Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setTime() has no return typehint specified.">',
            'Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setTime() has no return typehint specified.',
            'File Path: src/JUnit/TestCaseElement.php:52',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/TestCaseElement.php line 54, column 1" class="ERROR" classname="ERROR" file="src/JUnit/TestCaseElement.php" line="54">',
            '        <failure type="ERROR" message="Parameter #2 $value of method DOMElement::setAttribute() expects string, float given.">',
            'Parameter #2 $value of method DOMElement::setAttribute() expects string, float given.',
            'File Path: src/JUnit/TestCaseElement.php:54',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '    </testsuite>',
            '    <testsuite name="undefined" file="undefined" tests="1" failures="1">',
            '      <testcase name="undefined" class="ERROR" classname="ERROR" file="undefined">',
            '        <failure type="ERROR" message="Ignored error pattern #Variable \$undefined might not be defined.# was not matched in reported errors.">',
            'Ignored error pattern #Variable \$undefined might not be defined.# was not matched in reported errors.',
            'File Path: undefined',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '    </testsuite>',
            '  </testsuite>',
            '</testsuites>',
            '',
        ]), $actual);
    }

    public function testToInternalPsalm(): void
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';
        $source     = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(\file_get_contents(Fixtures::PSALM_CHECKSTYLE));

        $actual = (new JUnitConverter())->fromInternal($source);

        Aliases::isValidXml($actual);

        isSame(\implode("\n", [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<testsuites>',
            '  <testsuite name="CheckStyle" tests="5" failures="5">',
            '    <testsuite name="src/JUnit/TestCaseElement.php" file="src/JUnit/TestCaseElement.php" tests="5" failures="5">',
            '      <testcase name="src/JUnit/TestCaseElement.php line 34, column 21" class="ERROR" classname="ERROR" file="src/JUnit/TestCaseElement.php" line="34">',
            '        <failure type="ERROR" message="MissingReturnType: Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setName does not have a return type, expecting void">',
            'MissingReturnType: Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setName does not have a return type, expecting void',
            'File Path: src/JUnit/TestCaseElement.php:34:21',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/TestCaseElement.php line 42, column 21" class="ERROR" classname="ERROR" file="src/JUnit/TestCaseElement.php" line="42">',
            '        <failure type="ERROR" message="MissingReturnType: Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setClassname does not have a return type, expecting void">',
            'MissingReturnType: Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setClassname does not have a return type, expecting void',
            'File Path: src/JUnit/TestCaseElement.php:42:21',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/TestCaseElement.php line 52, column 21" class="ERROR" classname="ERROR" file="src/JUnit/TestCaseElement.php" line="52">',
            '        <failure type="ERROR" message="MissingReturnType: Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setTime does not have a return type, expecting void">',
            'MissingReturnType: Method JBZoo\CIReportConverter\JUnit\TestCaseElement::setTime does not have a return type, expecting void',
            'File Path: src/JUnit/TestCaseElement.php:52:21',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/TestCaseElement.php line 54, column 37" class="ERROR" classname="ERROR" file="src/JUnit/TestCaseElement.php" line="54">',
            '        <failure type="ERROR" message="InvalidScalarArgument: Argument 2 of JBZoo\CIReportConverter\JUnit\TestCaseElement::setAttribute expects string, float provided">',
            'InvalidScalarArgument: Argument 2 of JBZoo\CIReportConverter\JUnit\TestCaseElement::setAttribute expects string, float provided',
            'File Path: src/JUnit/TestCaseElement.php:54:37',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '      <testcase name="src/JUnit/TestCaseElement.php line 65, column 47" class="ERROR" classname="ERROR" file="src/JUnit/TestCaseElement.php" line="65">',
            '        <failure type="ERROR" message="PossiblyNullReference: Cannot call method createElement on possibly null value">',
            'PossiblyNullReference: Cannot call method createElement on possibly null value',
            'File Path: src/JUnit/TestCaseElement.php:65:47',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '    </testsuite>',
            '  </testsuite>',
            '</testsuites>',
            '',
        ]), $actual);
    }
}
