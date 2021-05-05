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
use JBZoo\CiReportConverter\Converters\PmdCpdConverter;
use JBZoo\CiReportConverter\Converters\TeamCityTestsConverter;

/**
 * Class ConverterPmdCpdTest
 *
 * @package JBZoo\PHPUnit
 */
class ConverterPmdCpdTest extends PHPUnit
{
    public function testToJUnit()
    {
        $source = (new PmdCpdConverter())
            ->setRootPath('/Users/smetdenis/Work/projects/jbzoo-ci-report-converter')
            ->toInternal(file_get_contents(Fixtures::PHPCPD_XML));

        $actual = (new JUnitConverter())->fromInternal($source);

        Aliases::isValidXml($actual);
        isSame(Fixtures::getExpectedFileContent(), $actual);
    }

    public function testToTeamCity()
    {
        $source = (new PmdCpdConverter())
            ->setRootPath('/Users/smetdenis/Work/projects/jbzoo-ci-report-converter')
            ->toInternal(file_get_contents(Fixtures::PHPCPD_XML));

        $actual = (new TeamCityTestsConverter(['show-datetime' => false], 42))
            ->fromInternal($source);

        isSame(Fixtures::getExpectedFileContent('txt'), $actual);
    }
}
