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

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Class CheckStyleExamplesTest
 */
class CheckStyleExamples extends TestCase
{
    /**
     * The short example which uses pipe as way to pass error report.
     */
    public function testPipelineWay(): void
    {
        echo shell_exec(                       # Execute command via shell and return the complete output as a string.
            'php ./vendor/bin/phpcs' .         # Path to bin of third-party tool (PHP Code Sniffer is just example).
            ' --report=checkstyle' .           # Output format of PHPcs. ci-report-converter expects it by default as `--input-format` option.
            ' --standard=PSR12 -q ./src' .     # The custom tool options. For phpcs `-q` is important!
            ' | ' .                            # The pipe operator, it passes the output of one command as input to another. See https://en.wikipedia.org/wiki/Pipeline_(Unix)
            ' php ./ci-report-converter'       # The converter does all the magic. Look at help description ( --help) to lean more about options and default values.
        );

        # Usually PHPUnit expects at least one assert in a test.
        # Otherwise, it may show useless warning messages. It depends on PHPUnit version and your configurations.
        # So, just in case, we make a fake assertion.
        Assert::assertTrue(true);
    }

    /**
     * The super detailed example which uses files as way to pass error report.
     */
    public function testXmlFileWay(): void
    {
        shell_exec(                                     # Execute command via shell and return the complete output as a string.
            'php ./vendor/bin/phpcs' .                  # Path to bin of third-party tool (PHP Code Sniffer is just example).
            ' --report=checkstyle' .                    # Output format of PHPcs. ci-report-converter expects it by default as `--input-format` option.
            ' --report-file=./build/phpcs-report.xml' . # Save result of phpcs work in XML file in "checkstyle" format.
            ' --standard=PSR12 -q ./src' .              # The custom tool options. For phpcs `-q` is important!
            ' || true' .                                # We don't expect any output of phpcs and ignore error exit codes.
            ' > /dev/null'                              # Lol, we are very self-confident. Actually, we need only XML file, that's it.
        );

        echo shell_exec(
            'php ./ci-report-converter' .               # The path to bin file of CI-Report-Converter. It depends of your installation way.
            ' --input-format=checkstyle' .              # Source reporting format. Default value is "checkstyle". I put it here just to show the option,
            ' --input-file=./build/phpcs-report.xml' .  # Using prepared file on previous step as source.
            ' --output-format=tc-tests' .               # Target reporting format. Default value is "tc-tests". I put it here just to show the option,
            ' --suite-name=PHPcs' .                     # Define the name of group. See screenshot below.
            ' --root-path=`pwd`'                        # Specify the root project path for pretty printing in UI. Default value is "." (dot, current dir).
        );

        Assert::assertTrue(true);
    }

    public function testPhpMd(): void
    {
        echo shell_exec(
            'php ./vendor/bin/phpmd ./src json' .
            ' cleancode,codesize,controversial,design,naming,unusedcode' .
            ' | ' .
            ' ./ci-report-converter --input-format=phpmd-json'
        );

        Assert::assertTrue(true);
    }

    public function testMagicNumberDetector(): void
    {
        shell_exec('php ./vendor/bin/phpmnd ./src --hint --xml-output=./build/phpmnd-report.xml --quiet');
        echo shell_exec(
            'php ./ci-report-converter' .
            ' --input-file=./build/phpmnd-report.xml' .
            ' --input-format=phpmnd' .
            ' --suite-name="Magic Number Detector"'
        );

        Assert::assertTrue(true);
    }

    public function testPmdCpd(): void
    {
        shell_exec('php ./vendor/bin/phpcpd.phar --min-tokens=20 ./src --log-pmd=./build/phpcpd-report.xml');
        echo shell_exec(
            'php ./ci-report-converter' .
            '  --input-file=./build/phpcpd-report.xml' .
            '  --input-format=pmd-cpd' .
            '  --suite-name="Copy&Paste Detector"'
        );

        Assert::assertTrue(true);
    }

    public function testPhpStan(): void
    {
        echo shell_exec(
            'php ./vendor/bin/phpstan analyse --error-format=checkstyle --no-progress ./vendor/jbzoo' .
            ' | ./ci-report-converter.phar --suite-name="PHPstan"'
        );

        Assert::assertTrue(true);
    }

    public function testPsalm(): void
    {
        echo shell_exec(
            'php ./vendor/bin/psalm.phar' .
            ' --config=./vendor/jbzoo/codestyle/psalm.xml' .
            ' --output-format=json' .
            ' ./vendor/jbzoo/jbdump/class.jbdump.php' .
            ' | ./ci-report-converter.phar --input-format=psalm-json'
        );

        Assert::assertTrue(true);
    }

    public function testPhan(): void
    {
        echo shell_exec(
            'php ./vendor/bin/phan.phar --allow-polyfill-parser --directory=./vendor/jbzoo/jbdump --output-mode=checkstyle' .
            ' | ./ci-report-converter.phar'
        );

        Assert::assertTrue(true);
    }
}
