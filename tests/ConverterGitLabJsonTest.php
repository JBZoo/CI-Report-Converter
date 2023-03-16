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
use JBZoo\CIReportConverter\Converters\GitLabJsonConverter;
use JBZoo\CIReportConverter\Converters\JUnitConverter;
use JBZoo\CIReportConverter\Converters\PmdCpdConverter;

/**
 * Class ConverterGitLabJsonTest
 * @package JBZoo\PHPUnit
 */
class ConverterGitLabJsonTest extends PHPUnit
{
    public function testJUnitSimple()
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceReport = (new JUnitConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(file_get_contents(Fixtures::PHPUNIT_JUNIT_SIMPLE));
        $targetReport = (new GitLabJsonConverter())
            ->setRootPath($pathPrefix)
            ->fromInternal($sourceReport);

        isSame(Fixtures::getExpectedFileContent('json'), $targetReport);
    }

    public function testJUnitNested()
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceReport = (new JUnitConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(file_get_contents(Fixtures::PHPUNIT_JUNIT_NESTED));
        $targetReport = (new GitLabJsonConverter())
            ->setRootPath($pathPrefix)
            ->fromInternal($sourceReport);

        isSame(Fixtures::getExpectedFileContent('json'), $targetReport);
    }

    public function testCodeStyle()
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceReport = (new CheckStyleConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(file_get_contents(Fixtures::PHPCS_CODESTYLE));
        $targetReport = (new GitLabJsonConverter())
            ->setRootPath($pathPrefix)
            ->setRootSuiteName('Tests')
            ->fromInternal($sourceReport);

        isSame(Fixtures::getExpectedFileContent('json'), $targetReport);
    }

    public function testPmdCpdXml()
    {
        $pathPrefix = '/Users/smetdenis/Work/projects/jbzoo-ci-report-converter';

        $sourceReport = (new PmdCpdConverter())
            ->setRootPath($pathPrefix)
            ->toInternal(file_get_contents(Fixtures::PHPCPD_XML));
        $targetReport = (new GitLabJsonConverter())
            ->setRootPath($pathPrefix)
            ->setRootSuiteName('Tests')
            ->fromInternal($sourceReport);

        isSame(Fixtures::getExpectedFileContent('json'), $targetReport);
    }
}
