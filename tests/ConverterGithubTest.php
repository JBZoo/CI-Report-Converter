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

        $sourceReport = (new JUnitConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(file_get_contents(Fixtures::PHPUNIT_JUNIT_SIMPLE));
        $targetReport = (new GithubCliConverter())
            ->setRootPath($pathPrefix)
            ->fromInternal($sourceReport);

        isSame(Fixtures::getExpectedFileContent('txt'), $targetReport);
    }

    public function testJUnitNested()
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceReport = (new JUnitConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(file_get_contents(Fixtures::PHPUNIT_JUNIT_NESTED));
        $targetReport = (new GithubCliConverter())
            ->setRootPath($pathPrefix)
            ->fromInternal($sourceReport);

        isSame(Fixtures::getExpectedFileContent('txt'), $targetReport);
    }

    public function testCodeStyle()
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceReport = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(file_get_contents(Fixtures::PHPCS_CODESTYLE));
        $targetReport = (new GithubCliConverter())
            ->setRootPath($pathPrefix)
            ->setRootSuiteName('Tests')
            ->fromInternal($sourceReport);

        isSame(Fixtures::getExpectedFileContent('txt'), $targetReport);
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
