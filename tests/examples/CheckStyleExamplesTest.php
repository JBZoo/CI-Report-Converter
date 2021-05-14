<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Class CheckStyleExamplesTest
 */
class CheckStyleExamplesTest extends TestCase
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
            ' php ./ci-report-converter.phar'  # The converter does all the magic. Look at help description ( --help) to lean more about options and default values.
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
        echo shell_exec(                                # Execute command via shell and return the complete output as a string.
            'php ./vendor/bin/phpcs' .                  # Path to bin of third-party tool (PHP Code Sniffer is just example).
            ' --report=checkstyle' .                    # Output format of PHPcs. ci-report-converter expects it by default as `--input-format` option.
            ' --report-file=./build/phpcs-report.xml' . # Save result of phpcs work in XML file in "checkstyle" format.
            ' --standard=PSR12 -q ./src' .              # The custom tool options. For phpcs `-q` is important!
            ' || true > /dev/null'                      # We don't expect any output of phpcs and ignore error exit codes.
                                                        # Lol, we are very self-confident. Actually, we need only XML file, that's it.
        );

        echo shell_exec(
            'php ./ci-report-converter.phar' .          # The path to bin file of CI-Report-Converter. It depends of your installation way.
            ' --input-format=checkstyle' .              # Source reporting format. Default value is "checkstyle". I put it here just to show the option,
            ' --input-file=./build/phpcs-report.xml' .  # Using prepared file on previous step as source.
            ' --output-format=tc-tests' .               # Target reporting format. Default value is "tc-tests". I put it here just to show the option,
            ' --suite-name=PHPcs' .                     # Define the name of group. See screenshot below.
            ' --root-path=`pwd`'                        # Specify the root project path for pretty printing in UI. Default value is "." (dot, current dir).
        );

        # The same reason like in testPipelineWay()
        Assert::assertTrue(true);
    }
}
