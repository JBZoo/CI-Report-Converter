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
use JBZoo\CIReportConverter\Converters\PlainTextConverter;
use JBZoo\CIReportConverter\Formats\PlainText\PlainText;
use JBZoo\CIReportConverter\Formats\PlainText\PlainTextCase;

final class ConverterPlainTextTest extends PHPUnit
{
    public function testPhpcsCodestyle(): void
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceReport = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(\file_get_contents(Fixtures::PHPCS_CODESTYLE));
        $targetReport = (new PlainTextConverter())
            ->setRootPath($pathPrefix)
            ->fromInternal($sourceReport);

        isSame(Fixtures::getExpectedFileContent('txt'), $targetReport);
    }

    public function testJUnitSimple(): void
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceReport = (new JUnitConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(\file_get_contents(Fixtures::PHPUNIT_JUNIT_SIMPLE));
        $targetReport = (new PlainTextConverter())
            ->setRootPath($pathPrefix)
            ->fromInternal($sourceReport);

        isSame(Fixtures::getExpectedFileContent('txt'), $targetReport);
    }

    public function testJUnitNested(): void
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceReport = (new JUnitConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(\file_get_contents(Fixtures::PHPUNIT_JUNIT_NESTED));
        $targetReport = (new PlainTextConverter())
            ->setRootPath($pathPrefix)
            ->fromInternal($sourceReport);

        isSame(Fixtures::getExpectedFileContent('txt'), $targetReport);
    }

    public function testCheckStyle(): void
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceReport = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(\file_get_contents(Fixtures::PHAN_CHECKSTYLE));
        $targetReport = (new PlainTextConverter())
            ->setRootPath($pathPrefix)
            ->setRootSuiteName('Tests')
            ->fromInternal($sourceReport);

        isSame(Fixtures::getExpectedFileContent('txt'), $targetReport);
    }

    public function testPlainTextPrinter(): void
    {
        $plainText      = new PlainText();
        $case0          = $plainText->addCase('src/Root.php');
        $case0->line    = 789;
        $case0->column  = null;
        $case0->message = 'Something went wrong #0';

        $suite1         = $plainText->addSuite('src/File.php');
        $case1          = $suite1->addCase('src/Class.php');
        $case1->line    = 123;
        $case1->column  = 4;
        $case1->message = "Something went wrong once again. It's a really really really long-long-long-long message.\n"
            . 'Another line.';

        $suite2         = $plainText->addSuite();
        $case2          = $suite2->addCase('src/AnotherFile.php');
        $case2->line    = 456;
        $case2->column  = 0;
        $case2->level   = PlainTextCase::LEVEL_WARNING;
        $case2->message = "Something\nwent\nwrong\n\n#2";

        $case3          = $suite2->addCase('src/SomeFiles.php');
        $case3->level   = PlainTextCase::LEVEL_DEBUG;
        $case3->message = \implode("\n", [
            'Failed asserting that two arrays are identical.',
            '--- Expected',
            '+++ Actual',
            '@@ @@',
            ' Array &0 (',
            "-    0 => 'asd'",
            '+    0 => 123',
            '+    1 => 123123',
            ' )',
        ]);

        $suite2->addCase();

        isSame(Fixtures::getExpectedFileContent('txt'), (string)$plainText);
    }
}
