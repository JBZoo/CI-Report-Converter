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

use JBZoo\CiReportConverter\Commands\Convert;
use JBZoo\CiReportConverter\Commands\ConvertMap;
use JBZoo\CiReportConverter\Commands\TeamCityStats;
use JBZoo\CiReportConverter\Converters\CheckStyleConverter;
use JBZoo\CiReportConverter\Converters\GithubCliConverter;
use JBZoo\CiReportConverter\Converters\JUnitConverter;
use JBZoo\CiReportConverter\Converters\PhpLocStatsTcConverter;
use JBZoo\CiReportConverter\Converters\PhpMdJsonConverter;
use JBZoo\CiReportConverter\Converters\TeamCityInspectionsConverter;
use JBZoo\CiReportConverter\Converters\TeamCityTestsConverter;
use JBZoo\Utils\Cli;
use JBZoo\Utils\Sys;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

use function JBZoo\Data\json;
use function JBZoo\Data\yml;

/**
 * Class CliCommandsTest
 * @package JBZoo\PHPUnit
 */
class CliCommandsTest extends PHPUnit
{
    public function testConvertCommandReadMe()
    {
        $helpMessage = $this->taskReal('convert', ['help' => null]);
        $helpMessage = implode("\n", [
            '',
            '```',
            '$ php ./vendor/bin/ci-report-converter convert --help',
            $helpMessage,
            '```',
            '',
        ]);

        isFileContains($helpMessage, PROJECT_ROOT . '/README.md');
    }

    public function testTcStatsCommandReadMe()
    {
        $helpMessage = $this->taskReal('teamcity:stats', ['help' => null]);
        $helpMessage = implode("\n", [
            '',
            '```',
            '$ php ./vendor/bin/ci-report-converter teamcity:stats --help',
            $helpMessage,
            '```',
            '',
        ]);

        isFileContains($helpMessage, PROJECT_ROOT . '/README.md');
    }

    public function testGitHubActionsYml()
    {
        $helpJson = json($this->taskReal('convert', ['help' => null, 'format' => 'json']));
        $actionYml = yml(PROJECT_ROOT . '/action.yml');

        $excludedOptions = [
            'help',
            'quiet',
            'verbose',
            'version',
            'ansi',
            'no-ansi',
            'no-interaction',
            //
            'tc-flow-id',
            'root-path'
        ];

        $expectedInputs = [];
        $expectedRunsArgs = ['convert'];
        foreach ($helpJson->findArray('definition.options') as $key => $option) {
            if (in_array($key, $excludedOptions, true)) {
                continue;
            }

            $expectedInputs[$key] = array_filter([
                'description' => strip_tags($option['description']),
                'default'     => $option['default'],
                'required'    => $option['is_value_required'],
            ]);

            $expectedRunsArgs[] = "--{$key}";
            $expectedRunsArgs[] = "\${{ inputs.{$key} }}";
        }

        $expectedRunsArgs[] = '-vvv';

        $expectedInputs['output-format']['default'] = GithubCliConverter::TYPE;
        $expectedInputs['input-file']['required'] = true;
        ksort($expectedInputs);

        $errorMessage = implode("\n", [
            "See: " . PROJECT_ROOT . "/action.yml",
            'Expected',
            '```',
            yml(['inputs' => $expectedInputs]),
            '```',
        ]);
        isSame($expectedInputs, $actionYml->getArray('inputs'), $errorMessage);

        $errorMessage = implode("\n", [
            "See: " . PROJECT_ROOT . "/action.yml",
            'Expected',
            '```',
            str_replace(["'\${{", "}}'"], ["\${{", "}}"], (string)yml($expectedRunsArgs)),
            '```',
        ]);
        isSame($expectedRunsArgs, $actionYml->findArray('runs.args'), $errorMessage);
    }

    /**
     * @depends testGitHubActionsYml
     */
    public function testGitHubActionsReadMe()
    {
        $inputs = yml(PROJECT_ROOT . '/action.yml')->findArray('inputs');
        $examples = [
            'input-file'    => './build/checkstyle.xml',
            'input-format'  => 'checkstyle',
            'output-file'   => './build/junit.xml',
            'output-format' => 'junit',
            'suite-name'    => 'My Tests',
            'non-zero-code' => 'yes',
        ];

        $expectedMessage = [
            '```yaml',
            '- uses: jbzoo/ci-report-converter@master # or see the specific version on releases page',
            '  with:'
        ];

        foreach ($inputs as $key => $input) {
            $expectedMessage[] = "    # {$input['description']}";

            if (isset($input['default'])) {
                $expectedMessage[] = "    # Default value: {$input['default']}";
            }

            if (isset($input['required']) && $input['required']) {
                $expectedMessage[] = '    # Required: true';
            }

            $expectedMessage[] = "    {$key}: {$examples[$key]}";
            $expectedMessage[] = '';
        }

        $expectedMessage[] = '```';

        isFileContains(implode("\n", $expectedMessage), PROJECT_ROOT . '/README.md');
    }

    public function testConvertCommandMapReadMe()
    {
        isSame(Fixtures::getExpectedFileContent('md'), $this->task('convert:map'));
        isSame(Fixtures::getExpectedFileContent('md'), $this->taskReal('convert:map'));
    }

    public function testConvertStatsUndefinedFile()
    {
        $output = $this->task('teamcity:stats', [
            'input-file'   => '/undefined/file.xml',
            'input-format' => 'pdepend-xml'
        ]);

        isSame("Warning: File \"/undefined/file.xml\" not found\n", $output);
    }

    public function testConvertStatsCustomFlowId()
    {
        $output = $this->task('teamcity:stats', [
            'input-file'   => Fixtures::PHPLOC_JSON,
            'input-format' => PhpLocStatsTcConverter::TYPE,
            'tc-flow-id'   => 10000
        ]);

        isContain(" flowId='10000'", $output);
    }

    public function testConvertCustomFlowId()
    {
        $output = $this->task('convert', [
            'input-format'  => CheckStyleConverter::TYPE,
            'output-format' => TeamCityTestsConverter::TYPE,
            'input-file'    => Fixtures::PSALM_CHECKSTYLE,
            'suite-name'    => "Test Suite",
            'root-path'     => "src",
            'tc-flow-id'    => "10101",
        ]);

        isContain(" flowId='10101'", $output);
    }

    public function testConvertToTcInspections()
    {
        $output = $this->task('convert', [
            'input-format'  => PhpMdJsonConverter::TYPE,
            'output-format' => TeamCityInspectionsConverter::TYPE,
            'input-file'    => Fixtures::PHPMD_JSON,
        ]);
        isContain("##teamcity[inspectionType id='PHPmd:UnusedFormalParameter' " .
            "name='UnusedFormalParameter' " .
            "category='PHPmd' " .
            "description='Issues found while checking coding standards'", $output);

        $output = $this->task('convert', [
            'input-format'  => PhpMdJsonConverter::TYPE,
            'output-format' => TeamCityInspectionsConverter::TYPE,
            'input-file'    => Fixtures::PHPMD_JSON,
            'suite-name'    => "Test Suite",
        ]);
        isContain("inspectionType id='Test Suite:UnusedFormalParameter' " .
            "name='UnusedFormalParameter' " .
            "category='Test Suite' " .
            "description='Issues found while checking coding standards'", $output);
    }

    /**
     * @depends testConvertToTcInspections
     */
    public function testNonZeroCode()
    {
        $output = null;
        try {
            $this->task('convert', [
                'input-format'  => PhpMdJsonConverter::TYPE,
                'output-format' => TeamCityInspectionsConverter::TYPE,
                'input-file'    => Fixtures::PHPMD_JSON,
                'non-zero-code' => 'yes'
            ]);
        } catch (\Exception $exception) {
            $output = $exception->getMessage();
        }

        isContain("##teamcity[inspectionType id='PHPmd:UnusedFormalParameter' " .
            "name='UnusedFormalParameter' " .
            "category='PHPmd' " .
            "description='Issues found while checking coding standards'", $output);
    }

    public function testConvertUndefinedFile()
    {
        $output = $this->task('convert', [
            'input-format'  => CheckStyleConverter::TYPE,
            'output-format' => JUnitConverter::TYPE,
            'input-file'    => '/undefined/file.xml',
            'suite-name'    => "Test Suite",
            'root-path'     => "src",
            'non-zero-code' => 'yes'
        ]);

        isSame("Warning: File \"/undefined/file.xml\" not found\n", $output);
    }

    public function testConvertCommand()
    {
        $output = $this->task('convert', [
            'input-format'  => CheckStyleConverter::TYPE,
            'output-format' => JUnitConverter::TYPE,
            'input-file'    => Fixtures::PSALM_CHECKSTYLE,
            'suite-name'    => "Test Suite",
            'root-path'     => "src",
        ]);

        isSame(implode("\n", [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<testsuites>',
            '  <testsuite name="Test Suite" tests="5" failures="5">',
            '    <testsuite name="JUnit/TestCaseElement.php" file="JUnit/TestCaseElement.php" tests="5" failures="5">',
            '      <testcase name="JUnit/TestCaseElement.php line 34, column 21" class="ERROR" classname="ERROR" file="JUnit/TestCaseElement.php" line="34">',
            '        <failure type="ERROR" message="MissingReturnType: Method JBZoo\CiReportConverter\JUnit\TestCaseElement::setName does not have a return type, expecting void">',
            'MissingReturnType: Method JBZoo\CiReportConverter\JUnit\TestCaseElement::setName does not have a return type, expecting void',
            'File Path: JUnit/TestCaseElement.php:34:21',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '      <testcase name="JUnit/TestCaseElement.php line 42, column 21" class="ERROR" classname="ERROR" file="JUnit/TestCaseElement.php" line="42">',
            '        <failure type="ERROR" message="MissingReturnType: Method JBZoo\CiReportConverter\JUnit\TestCaseElement::setClassname does not have a return type, expecting void">',
            'MissingReturnType: Method JBZoo\CiReportConverter\JUnit\TestCaseElement::setClassname does not have a return type, expecting void',
            'File Path: JUnit/TestCaseElement.php:42:21',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '      <testcase name="JUnit/TestCaseElement.php line 52, column 21" class="ERROR" classname="ERROR" file="JUnit/TestCaseElement.php" line="52">',
            '        <failure type="ERROR" message="MissingReturnType: Method JBZoo\CiReportConverter\JUnit\TestCaseElement::setTime does not have a return type, expecting void">',
            'MissingReturnType: Method JBZoo\CiReportConverter\JUnit\TestCaseElement::setTime does not have a return type, expecting void',
            'File Path: JUnit/TestCaseElement.php:52:21',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '      <testcase name="JUnit/TestCaseElement.php line 54, column 37" class="ERROR" classname="ERROR" file="JUnit/TestCaseElement.php" line="54">',
            '        <failure type="ERROR" message="InvalidScalarArgument: Argument 2 of JBZoo\CiReportConverter\JUnit\TestCaseElement::setAttribute expects string, float provided">',
            'InvalidScalarArgument: Argument 2 of JBZoo\CiReportConverter\JUnit\TestCaseElement::setAttribute expects string, float provided',
            'File Path: JUnit/TestCaseElement.php:54:37',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '      <testcase name="JUnit/TestCaseElement.php line 65, column 47" class="ERROR" classname="ERROR" file="JUnit/TestCaseElement.php" line="65">',
            '        <failure type="ERROR" message="PossiblyNullReference: Cannot call method createElement on possibly null value">',
            'PossiblyNullReference: Cannot call method createElement on possibly null value',
            'File Path: JUnit/TestCaseElement.php:65:47',
            'Severity : error',
            '</failure>',
            '      </testcase>',
            '    </testsuite>',
            '  </testsuite>',
            '</testsuites>',
            ''
        ]), $output);
    }

    /**
     * @param string $action
     * @param array  $params
     * @return string
     * @throws \Exception
     */
    public function task(string $action, array $params = []): string
    {
        $application = new Application();
        $application->add(new Convert());
        $application->add(new ConvertMap());
        $application->add(new TeamCityStats());
        $command = $application->find($action);

        $buffer = new BufferedOutput();
        $args = new StringInput(Cli::build('', $params));
        $exitCode = $command->run($args, $buffer);

        if ($exitCode) {
            throw new Exception($buffer->fetch());
        }

        return $buffer->fetch();
    }

    /**
     * @param string $action
     * @param array  $params
     * @return string
     */
    public function taskReal(string $action, array $params = []): string
    {
        $rootDir = PROJECT_ROOT;

        return Cli::exec(
            implode(' ', [
                Sys::getBinary(),
                "{$rootDir}/ci-report-converter.php --no-ansi",
                $action,
            ]),
            $params,
            $rootDir,
            false
        );
    }
}
