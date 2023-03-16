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
use JBZoo\CIReportConverter\Converters\PmdCpdConverter;
use JBZoo\CIReportConverter\Converters\TeamCityTestsConverter;

class ConverterPmdCpdTest extends PHPUnit
{
    public function testToJUnit(): void
    {
        $source = (new PmdCpdConverter())
            ->setRootPath('/Users/smetdenis/Work/projects/jbzoo-ci-report-converter')
            ->toInternal(\file_get_contents(Fixtures::PHPCPD_XML));

        $actual = (new JUnitConverter())->fromInternal($source);

        Aliases::isValidXml($actual);
        isSame(Fixtures::getExpectedFileContent(), $actual);
    }

    public function testToTeamCity(): void
    {
        $source = (new PmdCpdConverter())
            ->setRootPath('/Users/smetdenis/Work/projects/jbzoo-ci-report-converter')
            ->toInternal(\file_get_contents(Fixtures::PHPCPD_XML));

        $actual = (new TeamCityTestsConverter(['show-datetime' => false], 42))
            ->fromInternal($source);

        isSame(Fixtures::getExpectedFileContent('txt'), $actual);
    }
}
