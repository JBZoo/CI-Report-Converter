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

use JBZoo\CiReportConverter\Converters\JUnitConverter;
use JBZoo\CiReportConverter\Formats\JUnit\JUnit;
use JBZoo\CiReportConverter\Formats\Source\SourceCaseOutput;
use JBZoo\CiReportConverter\Formats\Source\SourceSuite;

/**
 * Class ConverterJUnitTest
 * @package JBZoo\PHPUnit
 */
class ConverterJUnitTest extends PHPUnit
{
    public function testConvertToInternal()
    {
        $junit = new JUnit();
        $suiteAll = $junit->addSuite('All');
        $suite1 = $suiteAll->addSuite('Suite #1');
        $suite1->addCase('Test #1.1')->setTime(1);
        $suite1->addCase('Test #1.2')->setTime(2);
        $suite2 = $suiteAll->addSuite('Suite #2');
        $suite2->addCase('Test #2.1')->setTime(3);
        $suite2->addCase('Test #2.2')->setTime(4);
        $suite2->addCase('Test #2.3')->setTime(5);
        $actual = (new JUnitConverter())->toInternal((string)$junit)->toArray();


        $collection = new SourceSuite();
        $suiteAll = $collection->addSuite('All');
        $suite1 = $suiteAll->addSuite('Suite #1');
        $suite1->addTestCase('Test #1.1')->time = 1;
        $suite1->addTestCase('Test #1.2')->time = 2;
        $suite2 = $suiteAll->addSuite('Suite #2');
        $suite2->addTestCase('Test #2.1')->time = 3;
        $suite2->addTestCase('Test #2.2')->time = 4;
        $suite2->addTestCase('Test #2.3')->time = 5;
        $expected = $suiteAll->toArray();

        isSame($expected, $actual['suites'][0]['suites'][0]);
    }

    public function testConvertToInternalReal()
    {
        $suiteAll = new SourceSuite('All');
        $suite1 = $suiteAll->addSuite('Suite #1');
        $suite1->addTestCase('Test #1.1')->time = 1;
        $suite1->addTestCase('Test #1.2')->time = 2;
        $suite2 = $suiteAll->addSuite('Suite #2');
        $suite2->addTestCase('Test #2.1')->time = 3;
        $suite2->addTestCase('Test #2.2')->time = 4;
        $suite2->addTestCase('Test #2.3')->time = 5;
        $junitActual = (new JUnitConverter())->fromInternal($suiteAll);


        $junitExpected = new JUnit();
        $suiteAll = $junitExpected->addSuite('All');
        $suite1 = $suiteAll->addSuite('Suite #1');
        $suite1->addCase('Test #1.1')->time = 1;
        $suite1->addCase('Test #1.2')->time = 2;
        $suite2 = $suiteAll->addSuite('Suite #2');
        $suite2->addCase('Test #2.1')->time = 3;
        $suite2->addCase('Test #2.2')->time = 4;
        $suite2->addCase('Test #2.3')->time = 5;

        isSame((string)$junitExpected, $junitActual);
    }

    public function testConvertToInternalRealFull()
    {
        // Fixtures
        $class = ExampleTest::class;
        $className = str_replace('\\', '.', $class);
        $filename = './tests/ExampleTest.php';
        $line = 28;

        $suite = new SourceSuite('Suite');
        $case = $suite->addTestCase('Test Name');
        $case->time = 0.001824;
        $case->file = $filename;
        $case->line = $line;
        $case->class = $class;
        $case->classname = $className;
        $case->assertions = 5;
        $case->stdOut = 'Some std output';
        $case->errOut = 'Some err output';
        $case->failure = new SourceCaseOutput('Failure', 'Failure Message', 'Failure Details');
        $case->error = new SourceCaseOutput('Error', 'Error Message', 'Error Details');
        $case->warning = new SourceCaseOutput('Warning', 'Warning Message', 'Warning Details');
        $case->skipped = new SourceCaseOutput('Skipped', 'Skipped Message', 'Skipped Details');

        isSame(implode("\n", [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<testsuites>',
            '  <testsuite name="Suite" tests="1" assertions="5" errors="1" warnings="1" failures="1" skipped="1" time="0.001824">',
            '    <testcase name="Test Name" class="JBZoo\PHPUnit\ExampleTest" classname="JBZoo.PHPUnit.ExampleTest" file="' . $filename . '" line="28" assertions="5" time="0.001824">',
            '      <failure type="Failure" message="Failure Message">Failure Details</failure>',
            '      <warning type="Warning" message="Warning Message">Warning Details</warning>',
            '      <error type="Error" message="Error Message">Error Details</error>',
            '      <system-out>Some std output',
            'Some err output</system-out>',
            '      <skipped/>',
            '    </testcase>',
            '  </testsuite>',
            '</testsuites>',
            '',
        ]), (new JUnitConverter())->fromInternal($suite));
    }

    public function testComplex()
    {
        $junitFiles = [
            Fixtures::PHPUNIT_JUNIT_NESTED,
            Fixtures::PHPUNIT_JUNIT_SIMPLE,
            //Fixtures::PHPCS_JUNIT,
            //Fixtures::PHPSTAN_JUNIT,
            //Fixtures::PSALM_JUNIT,
        ];

        foreach ($junitFiles as $junitFile) {
            $expectedXmlCode = file_get_contents($junitFile);

            $converter = new JUnitConverter();
            $source = $converter->toInternal($expectedXmlCode);
            $junit = $converter->fromInternal($source);

            Aliases::isValidXml($expectedXmlCode);
            Aliases::isValidXml($junit);
            Aliases::isSameXml($expectedXmlCode, $junit);
        }
    }
}
