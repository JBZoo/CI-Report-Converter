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
use JBZoo\CIReportConverter\Converters\TeamCityInspectionsConverter;

final class ConverterTeamCityInspectionsTest extends PHPUnit
{
    public function testPhpCsCodestyle(): void
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';
        $source = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(\file_get_contents(Fixtures::PHPCS_CODESTYLE));

        $actual = (new TeamCityInspectionsConverter(['show-datetime' => false]))
            ->setFlowId(1)
            ->fromInternal($source);

        isSame(Fixtures::getExpectedFileContent('txt'), $actual);
    }

    public function testPhanCodeStyle(): void
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';
        $source = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(\file_get_contents(Fixtures::PHAN_CHECKSTYLE));

        $actual = (new TeamCityInspectionsConverter(['show-datetime' => false]))
            ->setFlowId(1)
            ->fromInternal($source);

        isSame(Fixtures::getExpectedFileContent('txt'), $actual);
    }

    public function testJUnitSimple(): void
    {
        $source = (new JUnitConverter())
            ->setRootPath('/Users/smetdenis/Work/projects/jbzoo-ci-report-converter')
            ->toInternal(\file_get_contents(Fixtures::PHPUNIT_JUNIT_SIMPLE));

        $actual = (new TeamCityInspectionsConverter(['show-datetime' => false]))
            ->setFlowId(1)
            ->fromInternal($source);

        isSame(Fixtures::getExpectedFileContent('txt'), $actual);
    }

    public function testJUnitNested(): void
    {
        $source = (new JUnitConverter())
            ->setRootPath('/Users/smetdenis/Work/projects/jbzoo-ci-report-converter')
            ->toInternal(\file_get_contents(Fixtures::PHPUNIT_JUNIT_NESTED));

        $actual = (new TeamCityInspectionsConverter(['show-datetime' => false]))
            ->setFlowId(1)
            ->fromInternal($source);

        isSame(Fixtures::getExpectedFileContent('txt'), $actual);
    }
}
