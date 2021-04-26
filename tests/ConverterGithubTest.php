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
use JBZoo\CiReportConverter\Converters\GithubCliConverter;
use JBZoo\CiReportConverter\Converters\JUnitConverter;
use JBZoo\CiReportConverter\Formats\GithubActions\GithubActions;
use JBZoo\CiReportConverter\Formats\GithubActions\GithubCase;

/**
 * Class ConverterGithubTest
 * @package JBZoo\PHPUnit
 */
class ConverterGithubTest extends PHPUnit
{
    public function testJUnitSimple()
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceCode = (new JUnitConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(file_get_contents(Fixtures::PHPUNIT_JUNIT_SIMPLE));
        $targetSource = (new GithubCliConverter())
            ->setRootPath($pathPrefix)
            ->fromInternal($sourceCode);

        $file = 'tests/ExampleTest.php';

        isSame(implode("\n", [
            "::error file={$file},line=33::JBZoo\PHPUnit\ExampleTest::testInValid%0AFailed asserting that false is true.%0A%0Avendor/jbzoo/phpunit/src/functions/aliases.php:107%0Atests/ExampleTest.php:35",
            "::debug file={$file},line=38::Skipped",
            "::debug file={$file},line=43::Skipped",
            "::error file={$file},line=48::JBZoo\PHPUnit\ExampleTest::testFail%0ASome reason to fail%0A%0Avendor/jbzoo/phpunit/src/functions/aliases.php:51%0Atests/ExampleTest.php:50",
            "::error file={$file},line=53::Some echo output",
            "::error file={$file},line=71::Risky Test",
            "::error file={$file},line=75::JBZoo\PHPUnit\ExampleTest::testNotice%0AUndefined variable: aaa%0A%0Atests/ExampleTest.php:77",
            "::warning file={$file},line=80::JBZoo\PHPUnit\ExampleTest::testWarning%0ASome warning%0A%0Atests/ExampleTest.php:82",
            "::error file={$file},line=85::Some echo output",
        ]), $targetSource);
    }

    public function testJUnitNested()
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceCode = (new JUnitConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(file_get_contents(Fixtures::PHPUNIT_JUNIT_NESTED));
        $targetSource = (new GithubCliConverter())
            ->setRootPath($pathPrefix)
            ->fromInternal($sourceCode);

        $file = 'tests/ExampleTest.php';

        isSame(implode("\n", [
            "::error file={$file},line=38::JBZoo\PHPUnit\ExampleTest::testInValid%0AFailed asserting that false is true.%0A%0Avendor/jbzoo/phpunit/src/functions/aliases.php:107%0Atests/ExampleTest.php:40",
            "::debug file={$file},line=43::Skipped",
            "::debug file={$file},line=48::Skipped",
            "::error file={$file},line=53::JBZoo\PHPUnit\ExampleTest::testFail%0ASome reason to fail%0A%0Avendor/jbzoo/phpunit/src/functions/aliases.php:51%0Atests/ExampleTest.php:55",
            "::error file={$file},line=58::Some echo output",
            "::error file={$file},line=76::Risky Test",
            "::error file={$file},line=80::JBZoo\PHPUnit\ExampleTest::testNotice%0AUndefined variable: aaa%0A%0Atests/ExampleTest.php:82",
            "::warning file={$file},line=85::JBZoo\PHPUnit\ExampleTest::testWarning%0ASome warning%0A%0Atests/ExampleTest.php:87",
            "::error file={$file},line=90::Some echo output",
            "::error file={$file},line=96::JBZoo\PHPUnit\ExampleTest::testCompareArrays%0AFailed asserting that two arrays are identical.%0A--- Expected%0A+++ Actual%0A@@ @@%0A-Array &0 ()%0A+Array &0 (%0A+    0 => 1%0A+)%0A%0Avendor/jbzoo/phpunit/src/functions/aliases.php:197%0Atests/ExampleTest.php:98",
            "::error file={$file},line=101::JBZoo\PHPUnit\ExampleTest::testCompareString%0AFailed asserting that two strings are identical.%0A--- Expected%0A+++ Actual%0A@@ @@%0A-'132'%0A+'123'%0A%0Avendor/jbzoo/phpunit/src/functions/aliases.php:197%0Atests/ExampleTest.php:103",
        ]), $targetSource);
    }

    public function testCodeStyle()
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceCode = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(file_get_contents(Fixtures::PHPCS_CODESTYLE));
        $targetSource = (new GithubCliConverter())
            ->setRootPath($pathPrefix)
            ->setRootSuiteName('Tests')
            ->fromInternal($sourceCode);

        isSame(implode("\n", [
            "::group::Tests",
            "::error file=src/JUnit/JUnitXml.php,line=24,col=5::Visibility must be declared on all constants if your project supports PHP 7.1 or later%0A%0AVisibility must be declared on all constants if your project supports PHP 7.1 or later%0ARule     : PSR12.Properties.ConstantVisibility.NotFound%0AFile Path: src/JUnit/JUnitXml.php:24:5%0ASeverity : warning",
            "::error file=src/JUnit/JUnitXml.php,line=44,col=35::Opening brace should be on a new line%0A%0AOpening brace should be on a new line%0ARule     : Squiz.Functions.MultiLineFunctionDeclaration.BraceOnSameLine%0AFile Path: src/JUnit/JUnitXml.php:44:35%0ASeverity : error",
            "::error file=src/JUnit/JUnitXml.php,line=50,col=1::Expected 1 newline at end of file; 0 found%0A%0AExpected 1 newline at end of file; 0 found%0ARule     : PSR2.Files.EndFileNewline.NoneFound%0AFile Path: src/JUnit/JUnitXml.php:50%0ASeverity : error",
            "::endgroup::"
        ]), $targetSource);
    }

    public function testGithubActionsPrinter()
    {
        $ghActions = new GithubActions();
        $case0 = $ghActions->addCase('src/Root.php');
        $case0->line = 789;
        $case0->column = null;
        $case0->message = 'Something went wrong #0';

        $suite1 = $ghActions->addSuite('src/File.php');
        $case1 = $suite1->addCase('src/Class.php');
        $case1->line = 123;
        $case1->column = 4;
        $case1->message = 'Something went wrong #1';

        $suite2 = $ghActions->addSuite();
        $case2 = $suite2->addCase('src/AnotherFile.php');
        $case2->line = 456;
        $case2->column = 0;
        $case2->level = GithubCase::LEVEL_WARNING;
        $case2->message = "Something\nwent\nwrong\n\n#2\n";

        $case3 = $suite2->addCase('src/SomeFiles.php');
        $case3->level = GithubCase::LEVEL_DEBUG;
        $case3->message = implode("\n", [
            "Failed asserting that two arrays are identical.",
            "--- Expected",
            "+++ Actual",
            "@@ @@",
            " Array &0 (",
            "-    0 => 'asd'",
            "+    0 => 123",
            "+    1 => 123123",
            " )",
        ]);

        $suite2->addCase();

        isSame(implode("\n", [
            '::error file=src/Root.php,line=789::Something went wrong #0',

            '::group::src/File.php',
            '::error file=src/Class.php,line=123,col=4::Something went wrong #1',
            '::endgroup::',

            '::group::Undefined Suite Name',
            '::warning file=src/AnotherFile.php,line=456::Something%0Awent%0Awrong%0A%0A#2',
            "::debug file=src/SomeFiles.php::Failed asserting that two arrays are identical.%0A--- "
            . "Expected%0A+++ Actual%0A@@ @@%0A Array &0 (%0A-    0 => 'asd'%0A+    0 => 123%0A+    1 => 123123%0A )",
            '::error::Undefined Error Message',
            '::endgroup::'
        ]), (string)$ghActions);
    }
}
