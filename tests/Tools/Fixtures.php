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

/**
 * Class Fixtures
 *
 * @package JBZoo\PHPUnit
 */
class Fixtures
{
    public const ROOT      = __DIR__ . '/../../tests/fixtures';
    public const ROOT_ORIG = self::ROOT . '/origin';

    public const XSD_JUNIT      = self::ROOT . '/junit.xsd';
    public const XSD_CHECKSTYLE = self::ROOT . '/checkstyle.xsd';
    public const XSD_PMD        = self::ROOT . '/pmd.xsd';

    public const PHAN_CHECKSTYLE  = self::ROOT_ORIG . '/phan/checkstyle.xml';
    public const PHAN_CODECLIMATE = self::ROOT_ORIG . '/phan/codeclimate.json';
    public const PHAN_CSV         = self::ROOT_ORIG . '/phan/csv.csv';
    public const PHAN_JSON        = self::ROOT_ORIG . '/phan/json.json';
    public const PHAN_PYLINT      = self::ROOT_ORIG . '/phan/pylint.txt';

    public const PHPCPD_XML = self::ROOT_ORIG . '/phpcpd/pmd-cpd.xml';

    public const PHPCS_CODESTYLE = self::ROOT_ORIG . '/phpcs/codestyle.xml';
    public const PHPCS_CSV       = self::ROOT_ORIG . '/phpcs/csv.csv';
    public const PHPCS_EMACS     = self::ROOT_ORIG . '/phpcs/emacs.txt';
    public const PHPCS_JSON      = self::ROOT_ORIG . '/phpcs/json.json';
    public const PHPCS_JUNIT     = self::ROOT_ORIG . '/phpcs/junit.xml';
    public const PHPCS_XML       = self::ROOT_ORIG . '/phpcs/phpcs.xml';

    public const PHPLOC_CSV  = self::ROOT_ORIG . '/phploc/csv.csv';
    public const PHPLOC_JSON = self::ROOT_ORIG . '/phploc/json.json';
    public const PHPLOC_XML  = self::ROOT_ORIG . '/phploc/phploc.xml';

    public const PHPMD_JSON = self::ROOT_ORIG . '/phpmd/json.json';
    public const PHPMD_XML  = self::ROOT_ORIG . '/phpmd/pmd.xml';

    public const PHPMND_XML = self::ROOT_ORIG . '/phpmnd/phpmnd.xml';

    public const PHPSTAN_CHECKSTYLE = self::ROOT_ORIG . '/phpstan/checkstyle.xml';
    public const PHPSTAN_GITHUB     = self::ROOT_ORIG . '/phpstan/github.txt';
    public const PHPSTAN_GITLAB     = self::ROOT_ORIG . '/phpstan/gitlab.json';
    public const PHPSTAN_JSON       = self::ROOT_ORIG . '/phpstan/json.json';
    public const PHPSTAN_JUNIT      = self::ROOT_ORIG . '/phpstan/junit.xml';

    public const PHPUNIT_JUNIT_NESTED = self::ROOT_ORIG . '/phpunit/junit-nested.xml';
    public const PHPUNIT_JUNIT_SIMPLE = self::ROOT_ORIG . '/phpunit/junit-simple.xml';
    public const PHPUNIT_TEAMCITY     = self::ROOT_ORIG . '/phpunit/teamcity.txt';
    public const PHPUNIT_CLOVER       = self::ROOT_ORIG . '/phpunit/clover.xml';

    public const PSALM_CHECKSTYLE = self::ROOT_ORIG . '/psalm/checkstyle.xml';
    public const PSALM_EMACS      = self::ROOT_ORIG . '/psalm/emacs.txt';
    public const PSALM_GITHUB     = self::ROOT_ORIG . '/psalm/github.txt';
    public const PSALM_JSON       = self::ROOT_ORIG . '/psalm/json.json';
    public const PSALM_JUNIT      = self::ROOT_ORIG . '/psalm/junit.xml';
    public const PSALM_PYLINT     = self::ROOT_ORIG . '/psalm/pylint.txt';
    public const PSALM_SONARQUBE  = self::ROOT_ORIG . '/psalm/sonarqube.json';
    public const PSALM_XML        = self::ROOT_ORIG . '/psalm/xml.xml';

    public const PHP_DEPEND_XML     = self::ROOT_ORIG . '/pdepend/pdepend-old.xml';
    public const PHP_SUMMARY_XML    = self::ROOT_ORIG . '/pdepend/summary.xml';
    public const PHP_DEPENDENCY_XML = self::ROOT_ORIG . '/pdepend/dependency.xml';
    public const PHP_JDEPEND_XML    = self::ROOT_ORIG . '/pdepend/jdepend.xml';

    public const PHP_METRICS_XML     = self::ROOT_ORIG . '/phpmetrics/phpmetrics.xml';
    public const PHP_METRICS_JSON    = self::ROOT_ORIG . '/phpmetrics/phpmetrics.json';
    public const PHP_METRICS_PMD_XML = self::ROOT_ORIG . '/phpmetrics/pmd.xml';

    /**
     * @param string $fileExt
     * @return string
     */
    public static function getExpectedFileContent(string $fileExt = 'xml'): string
    {
        $filename = str_replace('__', '/', getTestName());
        return file_get_contents(dirname(__DIR__) . "/fixtures/test-cases/{$filename}.{$fileExt}");
    }
}
