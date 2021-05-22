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

        isSame(Fixtures::getExpectedFileContent('txt'), $actual);
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

        isSame(Fixtures::getExpectedFileContent('txt'), $actual);
    }

    public function testJUnitSimple()
    {
        $source = (new JUnitConverter())
            ->setRootPath('/Users/smetdenis/Work/projects/jbzoo-ci-report-converter')
            ->toInternal(file_get_contents(Fixtures::PHPUNIT_JUNIT_SIMPLE));

        $actual = (new TeamCityInspectionsConverter(['show-datetime' => false]))
            ->setFlowId(1)
            ->fromInternal($source);

        isSame(Fixtures::getExpectedFileContent('txt'), $actual);
    }

    public function testJUnitNested()
    {
        $source = (new JUnitConverter())
            ->setRootPath('/Users/smetdenis/Work/projects/jbzoo-ci-report-converter')
            ->toInternal(file_get_contents(Fixtures::PHPUNIT_JUNIT_NESTED));

        $actual = (new TeamCityInspectionsConverter(['show-datetime' => false]))
            ->setFlowId(1)
            ->fromInternal($source);

        isSame(Fixtures::getExpectedFileContent('txt'), $actual);
    }
}
