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

use JBZoo\CiReportConverter\Converters\JUnitStatsTcConverter;
use JBZoo\CiReportConverter\Converters\PhpDependStatsTcConverter;
use JBZoo\CiReportConverter\Converters\PhpLocStatsTcConverter;
use JBZoo\CiReportConverter\Converters\PhpMetricsStatsTcConverter;
use JBZoo\CiReportConverter\Converters\PhpUnitCloverStatsTcConverter;

/**
 * Class ConverterTeamCityStatsTest
 * @package JBZoo\PHPUnit
 */
class ConverterTeamCityStatsTest extends PHPUnit
{
    public function testPhpLocJson()
    {
        $converter = (new PhpLocStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(file_get_contents(Fixtures::PHPLOC_JSON));
        $output = $converter->fromInternalMetric($sourceCode);

        isSame(Fixtures::getExpectedFileContent('txt'), $output);
    }

    public function testPhpDependXml()
    {
        $converter = (new PhpDependStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(file_get_contents(Fixtures::PHP_DEPEND_XML));
        $output = $converter->fromInternalMetric($sourceCode);

        isSame(Fixtures::getExpectedFileContent('txt'), $output);
    }

    public function testPhpMetricsXml()
    {
        $converter = (new PhpMetricsStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(file_get_contents(Fixtures::PHP_METRICS_XML));
        $output = $converter->fromInternalMetric($sourceCode);

        isSame(Fixtures::getExpectedFileContent('txt'), $output);
    }

    public function testPhpUnitCloverXml()
    {
        $converter = (new PhpUnitCloverStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(file_get_contents(Fixtures::PHPUNIT_CLOVER));
        $output = $converter->fromInternalMetric($sourceCode);

        isSame(Fixtures::getExpectedFileContent('txt'), $output);
    }

    public function testJUnitXml()
    {
        $converter = (new JUnitStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(file_get_contents(Fixtures::PHPUNIT_JUNIT_NESTED));
        $output = $converter->fromInternalMetric($sourceCode);

        isSame(Fixtures::getExpectedFileContent('txt'), $output);
    }
}
