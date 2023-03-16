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

use JBZoo\CIReportConverter\Converters\JUnitStatsTcConverter;
use JBZoo\CIReportConverter\Converters\PhpDependStatsTcConverter;
use JBZoo\CIReportConverter\Converters\PhpLocStatsTcConverter;
use JBZoo\CIReportConverter\Converters\PhpMetricsStatsTcConverter;
use JBZoo\CIReportConverter\Converters\PhpUnitCloverStatsTcConverter;

class ConverterTeamCityStatsTest extends PHPUnit
{
    public function testPhpLocJson(): void
    {
        $converter = (new PhpLocStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(\file_get_contents(Fixtures::PHPLOC_JSON));
        $output     = $converter->fromInternalMetric($sourceCode);

        isSame(Fixtures::getExpectedFileContent('txt'), $output);
    }

    public function testPhpDependXml(): void
    {
        $converter = (new PhpDependStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(\file_get_contents(Fixtures::PHP_DEPEND_XML));
        $output     = $converter->fromInternalMetric($sourceCode);

        isSame(Fixtures::getExpectedFileContent('txt'), $output);
    }

    public function testPhpMetricsXml(): void
    {
        $converter = (new PhpMetricsStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(\file_get_contents(Fixtures::PHP_METRICS_XML));
        $output     = $converter->fromInternalMetric($sourceCode);

        isSame(Fixtures::getExpectedFileContent('txt'), $output);
    }

    public function testPhpUnitCloverXml(): void
    {
        $converter = (new PhpUnitCloverStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(\file_get_contents(Fixtures::PHPUNIT_CLOVER));
        $output     = $converter->fromInternalMetric($sourceCode);

        isSame(Fixtures::getExpectedFileContent('txt'), $output);
    }

    public function testJUnitXml(): void
    {
        $converter = (new JUnitStatsTcConverter(['show-datetime' => false], 1));

        $sourceCode = $converter->toInternalMetric(\file_get_contents(Fixtures::PHPUNIT_JUNIT_NESTED));
        $output     = $converter->fromInternalMetric($sourceCode);

        isSame(Fixtures::getExpectedFileContent('txt'), $output);
    }
}
