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

use JBZoo\CiReportConverter\Formats\Source\SourceCase;
use JBZoo\CiReportConverter\Formats\Source\SourceCaseOutput;
use JBZoo\CiReportConverter\Formats\Source\SourceSuite;

/**
 * Class FormatSourceTest
 * @package JBZoo\PHPUnit
 */
class FormatSourceTest extends PHPUnit
{
    public function testCollection()
    {
        $suite = new SourceSuite('Suite');
        isFalse($suite->hasSubSuites());
        $suite->addTestCase('Case #1')->time = 11;
        $suite->addTestCase('Case #2')->time = '2.2';
        $suite->addTestCase('Case #3')->failure = 'Failed';
        isFalse($suite->hasSubSuites());
        $suite->file = __FILE__;

        $subSuite = $suite->addSuite('Sub Suite');
        $subSuite->addTestCase('Case #3')->time = 0;
        isTrue($suite->hasSubSuites());

        isSame([
            "data"   => [
                '_node'   => 'SourceSuite',
                'name'    => 'Suite',
                'file'    => __FILE__,
                'time'    => 13.2,
                'tests'   => 4,
                'failure' => 1,
            ],
            "cases"  => [
                ['_node' => 'SourceCase', "name" => "Case #1", 'time' => 11.0],
                ['_node' => 'SourceCase', "name" => "Case #2", 'time' => 2.2],
                ['_node' => 'SourceCase', "name" => "Case #3", 'failure' => 'Failed']
            ],
            "suites" => [
                [
                    "data"   => ['_node' => 'SourceSuite', "name" => "Sub Suite", 'tests' => 1],
                    "cases"  => [['_node' => 'SourceCase', "name" => "Case #3", 'time' => 0.0]],
                    "suites" => [],
                ]
            ],
        ], $suite->toArray());
    }

    public function testSuiteAggregationUtilities()
    {
        $suite = new SourceSuite('Suite');
        $suite->addTestCase('Case #1');
        isSame(null, $suite->getTime());


        $suite = new SourceSuite('Suite');
        $suite->addTestCase('Case #1')->time = 11;
        $suite->addTestCase('Case #2');
        $suite->addTestCase('Case #3')->time = '2.2';
        isSame(13.2, $suite->getTime());


        $suite = new SourceSuite('Suite');
        $subSuite = $suite->addSuite('Suite 2');
        $suite->addTestCase('Case #1')->time = 1;
        $suite->addTestCase('Case #2')->time = 2;
        $subSuite->addTestCase('Case #3')->time = 0.0001;
        isSame(3.0001, $suite->getTime());
    }

    public function testSuiteObject()
    {
        $suite = new SourceSuite(' Suite ');
        isSame('Suite', $suite->name);
        isSame(null, $suite->file);
        isSame(null, $suite->class);
        isSame([
            'data'   => ['_node' => 'SourceSuite', 'name' => 'Suite'],
            'cases'  => [],
            'suites' => [],
        ], $suite->toArray());

        $suite->class = self::class;
        $suite->file = '/some/file/name.php';

        isSame([
            'data'   => [
                '_node' => 'SourceSuite',
                'name'  => 'Suite',
                'file'  => '/some/file/name.php',
                'class' => __CLASS__,
            ],
            'cases'  => [],
            'suites' => [],
        ], $suite->toArray());
    }

    public function testAllProperties()
    {
        $suite = new SourceSuite('Suite');
        $case = $suite->addTestCase('Test Name');
        $case->time = 0.001824;
        $case->file = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php';
        $case->line = 28;
        $case->class = ExampleTest::class;
        $case->classname = str_replace('\\', '.', ExampleTest::class);
        $case->assertions = 5;
        $case->stdOut = 'Some std output';
        $case->errOut = 'Some err output';
        $case->failure = new SourceCaseOutput('Failure', 'Failure Message', 'Failure Details');
        $case->warning = new SourceCaseOutput('Warning', 'Warning Message', 'Warning Details');
        $case->error = new SourceCaseOutput('Error', 'Error Message', 'Error Details');
        $case->skipped = new SourceCaseOutput('Skipped', 'Skipped Message', 'Skipped Details');

        isSame([
            'data'   => [
                '_node'      => 'SourceSuite',
                'name'       => 'Suite',
                'time'       => 0.001824,
                'tests'      => 1,
                'assertions' => 5,
                'errors'     => 1,
                'warnings'   => 1,
                'failure'    => 1,
                'skipped'    => 1,
            ],
            'cases'  => [
                [
                    '_node'      => 'SourceCase',
                    'name'       => 'Test Name',
                    'class'      => ExampleTest::class,
                    'classname'  => 'JBZoo.PHPUnit.ExampleTest',
                    'file'       => '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/tests/ExampleTest.php',
                    'line'       => 28,
                    'stdOut'     => 'Some std output',
                    'errOut'     => 'Some err output',
                    'time'       => 0.001824,
                    'assertions' => 5,
                    'failure'    => [
                        'type'    => 'Failure',
                        'message' => 'Failure Message',
                        'details' => 'Failure Details',
                    ],
                    'error'      => [
                        'type'    => 'Error',
                        'message' => 'Error Message',
                        'details' => 'Error Details',
                    ],
                    'warning'    => [
                        'type'    => 'Warning',
                        'message' => 'Warning Message',
                        'details' => 'Warning Details',
                    ],
                    'skipped'    => [
                        'type'    => 'Skipped',
                        'message' => 'Skipped Message',
                        'details' => 'Skipped Details',
                    ]
                ]
            ],
            'suites' => [],
        ], $suite->toArray());
    }

    public function testCaseObject()
    {
        $case = new SourceCase(' Case ');
        isSame(['_node' => 'SourceCase', 'name' => 'Case'], $case->toArray());

        $case->class = self::class;
        $case->line = 100;
        $case->file = '/some/file/name.php';
        $case->assertions = 10;
        $case->actual = 20;
        $case->expected = 30;

        isSame([
            '_node'      => 'SourceCase',
            'name'       => 'Case',
            'class'      => __CLASS__,
            'file'       => '/some/file/name.php',
            'line'       => 100,
            'assertions' => 10,
            'actual'     => '20',
            'expected'   => '30',
        ], $case->toArray());

        isSame(null, $case->getTime());
        $case->time = '123.456789';
        isSame(123.456789, $case->time);
        isSame('123', $case->getTime(0));
        isSame('123.457', $case->getTime(3));
        isSame('123.456789', $case->getTime());
    }

    public function testUsingProperties()
    {
        $suite = new SourceCase('Case');
        isSame(null, $suite->invalid_prop);
        isFalse(isset($suite->invalid_prop));

        isTrue(isset($suite->name));

        isFalse(isset($suite->time));
        isSame(null, $suite->time);
        $suite->time = '1';
        isSame(1.0, $suite->time);
    }

    public function testSettingInvalidProperty()
    {
        $this->expectException(\JBZoo\CiReportConverter\Formats\Source\Exception::class);
        $this->expectExceptionMessage('Undefined property "invalid_prop"');

        $suite = new SourceCase('Case');
        $suite->invalid_prop = 100;
    }
}
