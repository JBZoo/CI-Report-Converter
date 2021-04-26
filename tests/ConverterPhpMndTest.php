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

use JBZoo\CiReportConverter\Converters\PhpMndConverter;
use JBZoo\CiReportConverter\Converters\TeamCityInspectionsConverter;
use JBZoo\CiReportConverter\Converters\TeamCityTestsConverter;

/**
 * Class ConverterPhpMndTest
 *
 * @package JBZoo\PHPUnit
 */
class ConverterPhpMndTest extends PHPUnit
{
    public function testToTcTests()
    {
        $source = (new PhpMndConverter())
            //->setRootPath('/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/jbzoo/utils')
            ->toInternal(file_get_contents(Fixtures::PHPMND_XML));

        $converter = (new TeamCityTestsConverter(['show-datetime' => false], 1))->fromInternal($source);

        isSame(implode("\n\n", [
            "##teamcity[testCount count='8' flowId='1']",
            "##teamcity[testSuiteStarted name='PHPmnd' flowId='1']",
            "##teamcity[testSuiteStarted name='src/IP.php' locationHint='php_qn://src/IP.php::\src/IP.php' flowId='1']",
            "##teamcity[testStarted name='src/IP.php line 81, column 36' locationHint='php_qn://src/IP.php::\Magic Number::src/IP.php line 81, column 36' flowId='1']",
            "##teamcity[testFailed name='src/IP.php line 81, column 36' message='File Path  : src/IP.php:81:36' details=' Snippet    : `while (count(\$blocks) < 4) {`|n Suggestions: Env::VAR_INT; FS::PERM_ALL_READ; Url::URL_JOIN_QUERY|n ' flowId='1']",
            "##teamcity[testFinished name='src/IP.php line 81, column 36' flowId='1']",
            "##teamcity[testStarted name='src/IP.php line 137' locationHint='php_qn://src/IP.php::\Magic Number::src/IP.php line 137' flowId='1']",
            "##teamcity[testFailed name='src/IP.php line 137' message='File Path: src/IP.php:137' details=' Snippet  : `} elseif ((\$ipAddressLong & 0xC0000000) === 0x80000000) {`|n ' flowId='1']",
            "##teamcity[testFinished name='src/IP.php line 137' flowId='1']",
            "##teamcity[testStarted name='src/IP.php line 139' locationHint='php_qn://src/IP.php::\Magic Number::src/IP.php line 139' flowId='1']",
            "##teamcity[testFailed name='src/IP.php line 139' message='File Path: src/IP.php:139' details=' Snippet  : `} elseif ((\$ipAddressLong & 0xE0000000) === 0xC0000000) {`|n ' flowId='1']",
            "##teamcity[testFinished name='src/IP.php line 139' flowId='1']",
            "##teamcity[testSuiteFinished name='src/IP.php' flowId='1']",
            "##teamcity[testSuiteStarted name='tests/CliTest.php' locationHint='php_qn://tests/CliTest.php::\\tests/CliTest.php' flowId='1']",
            "##teamcity[testStarted name='tests/CliTest.php line 107, column 44' locationHint='php_qn://tests/CliTest.php::\Magic Number::tests/CliTest.php line 107, column 44' flowId='1']",
            "##teamcity[testFailed name='tests/CliTest.php line 107, column 44' message='File Path  : tests/CliTest.php:107:44' details=' Snippet    : `isTrue(Cli::getNumberOfColumns() >= 80);`|n Suggestions: Cli::DEFAULT_WIDTH; Url::PORT_HTTP|n ' flowId='1']",
            "##teamcity[testFinished name='tests/CliTest.php line 107, column 44' flowId='1']",
            "##teamcity[testSuiteFinished name='tests/CliTest.php' flowId='1']",
            "##teamcity[testSuiteStarted name='src/Timer.php' locationHint='php_qn://src/Timer.php::\src/Timer.php' flowId='1']",
            "##teamcity[testStarted name='src/Timer.php line 49, column 56' locationHint='php_qn://src/Timer.php::\Magic Number::src/Timer.php line 49, column 56' flowId='1']",
            "##teamcity[testFailed name='src/Timer.php line 49, column 56' message='File Path: src/Timer.php:49:56' details=' Snippet  : `return \$time . |' |' . \$unit . (\$time === 1.0 ? |'|' : |'s|');`|n ' flowId='1']",
            "##teamcity[testFinished name='src/Timer.php line 49, column 56' flowId='1']",
            "##teamcity[testSuiteFinished name='src/Timer.php' flowId='1']",
            "##teamcity[testSuiteStarted name='src/Dates.php' locationHint='php_qn://src/Dates.php::\src/Dates.php' flowId='1']",
            "##teamcity[testStarted name='src/Dates.php line 112, column 23' locationHint='php_qn://src/Dates.php::\Magic Number::src/Dates.php line 112, column 23' flowId='1']",
            "##teamcity[testFailed name='src/Dates.php line 112, column 23' message='File Path: src/Dates.php:112:23' details=' Snippet  : `return \$time > 10000;`|n ' flowId='1']",
            "##teamcity[testFinished name='src/Dates.php line 112, column 23' flowId='1']",
            "##teamcity[testStarted name='src/Dates.php line 212, column 23' locationHint='php_qn://src/Dates.php::\Magic Number::src/Dates.php line 212, column 23' flowId='1']",
            "##teamcity[testFailed name='src/Dates.php line 212, column 23' message='File Path  : src/Dates.php:212:23' details=' Snippet    : `if (\$seconds < 2) {`|n Suggestions: Env::VAR_BOOL; Cli::STDERR; FS::PERM_ALL_WRITE; Url::URL_JOIN_PATH|n ' flowId='1']",
            "##teamcity[testFinished name='src/Dates.php line 212, column 23' flowId='1']",
            "##teamcity[testSuiteFinished name='src/Dates.php' flowId='1']",
            "##teamcity[testSuiteStarted name='src/Http.php' locationHint='php_qn://src/Http.php::\src/Http.php' flowId='1']",
            "##teamcity[testStarted name='src/Http.php line 166, column 45' locationHint='php_qn://src/Http.php::\Magic Number::src/Http.php line 166, column 45' flowId='1']",
            "##teamcity[testFailed name='src/Http.php line 166, column 45' message='File Path  : src/Http.php:166:45' details=' Snippet    : `if (count(\$exploded) === 2) {`|n Suggestions: Env::VAR_BOOL; Cli::STDERR; FS::PERM_ALL_WRITE; Url::URL_JOIN_PATH|n ' flowId='1']",
            "##teamcity[testFinished name='src/Http.php line 166, column 45' flowId='1']",
            "##teamcity[testSuiteFinished name='src/Http.php' flowId='1']",
            "##teamcity[testSuiteFinished name='PHPmnd' flowId='1']",
        ]), trim($converter));
    }

    public function testToTcInspections()
    {
        $source = (new PhpMndConverter())
            ->setRootSuiteName('PHP Magic Numbers')
            ->toInternal(file_get_contents(Fixtures::PHPMND_XML));

        $converter = (new TeamCityInspectionsConverter(['show-datetime' => false], 1))->fromInternal($source);

        isSame(implode("\n\n", [
            "##teamcity[inspectionType id='PHP Magic Numbers:Magic Number' name='Magic Number' category='PHP Magic Numbers' description='Issues found while checking coding standards' flowId='1']",
            "##teamcity[inspection typeId='PHP Magic Numbers:Magic Number' file='src/IP.php' line='81' message='------------------------------------------------------------------------------------------------------------------------|nsrc/IP.php line 81, column 36|nFile Path  : src/IP.php:81:36|n Snippet    : `while (count(\$blocks) < 4) {`|n Suggestions: Env::VAR_INT; FS::PERM_ALL_READ; Url::URL_JOIN_QUERY' SEVERITY='WARNING' flowId='1']",
            "##teamcity[inspection typeId='PHP Magic Numbers:Magic Number' file='src/IP.php' line='137' message='------------------------------------------------------------------------------------------------------------------------|nsrc/IP.php line 137|nFile Path: src/IP.php:137|n Snippet  : `} elseif ((\$ipAddressLong & 0xC0000000) === 0x80000000) {`' SEVERITY='WARNING' flowId='1']",
            "##teamcity[inspection typeId='PHP Magic Numbers:Magic Number' file='src/IP.php' line='139' message='------------------------------------------------------------------------------------------------------------------------|nsrc/IP.php line 139|nFile Path: src/IP.php:139|n Snippet  : `} elseif ((\$ipAddressLong & 0xE0000000) === 0xC0000000) {`' SEVERITY='WARNING' flowId='1']",
            "##teamcity[inspection typeId='PHP Magic Numbers:Magic Number' file='tests/CliTest.php' line='107' message='------------------------------------------------------------------------------------------------------------------------|ntests/CliTest.php line 107, column 44|nFile Path  : tests/CliTest.php:107:44|n Snippet    : `isTrue(Cli::getNumberOfColumns() >= 80);`|n Suggestions: Cli::DEFAULT_WIDTH; Url::PORT_HTTP' SEVERITY='WARNING' flowId='1']",
            "##teamcity[inspection typeId='PHP Magic Numbers:Magic Number' file='src/Timer.php' line='49' message='------------------------------------------------------------------------------------------------------------------------|nsrc/Timer.php line 49, column 56|nFile Path: src/Timer.php:49:56|n Snippet  : `return \$time . |' |' . \$unit . (\$time === 1.0 ? |'|' : |'s|');`' SEVERITY='WARNING' flowId='1']",
            "##teamcity[inspection typeId='PHP Magic Numbers:Magic Number' file='src/Dates.php' line='112' message='------------------------------------------------------------------------------------------------------------------------|nsrc/Dates.php line 112, column 23|nFile Path: src/Dates.php:112:23|n Snippet  : `return \$time > 10000;`' SEVERITY='WARNING' flowId='1']",
            "##teamcity[inspection typeId='PHP Magic Numbers:Magic Number' file='src/Dates.php' line='212' message='------------------------------------------------------------------------------------------------------------------------|nsrc/Dates.php line 212, column 23|nFile Path  : src/Dates.php:212:23|n Snippet    : `if (\$seconds < 2) {`|n Suggestions: Env::VAR_BOOL; Cli::STDERR; FS::PERM_ALL_WRITE; Url::URL_JOIN_PATH' SEVERITY='WARNING' flowId='1']",
            "##teamcity[inspection typeId='PHP Magic Numbers:Magic Number' file='src/Http.php' line='166' message='------------------------------------------------------------------------------------------------------------------------|nsrc/Http.php line 166, column 45|nFile Path  : src/Http.php:166:45|n Snippet    : `if (count(\$exploded) === 2) {`|n Suggestions: Env::VAR_BOOL; Cli::STDERR; FS::PERM_ALL_WRITE; Url::URL_JOIN_PATH' SEVERITY='WARNING' flowId='1']",
        ]), trim($converter));
    }
}
