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
use JBZoo\CIReportConverter\Converters\PhpMdJsonConverter;

/**
 * Class PhpmdJson2JUnitTest
 *
 * @package JBZoo\PHPUnit
 */
class ConverterPhpmdJsonTest extends PHPUnit
{
    public function testPhpmdJson2JUnit()
    {
        $actual = (new PhpMdJsonConverter())
            ->toInternal(file_get_contents(Fixtures::PHPMD_JSON));

        $actual = (new JUnitConverter())->fromInternal($actual);
        Aliases::isValidXml($actual);

        isSame(implode("\n", [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<testsuites>',
            '  <testsuite name="PHPmd" tests="10" failures="10">',
            '    <testsuite name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Application.php" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Application.php" tests="2" failures="2">',
            '      <testcase name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Application.php line 26" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Application.php" line="26">',
            '        <failure type="UnusedFormalParameter" message="Avoid unused parameters such as \'$input\'.">',
            'Avoid unused parameters such as \'$input\'.',
            'Rule     : Unused Code Rules / UnusedFormalParameter / Priority: 3',
            'PHP Mute : @SuppressWarnings(PHPMD.UnusedFormalParameter)',
            'File Path: /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Application.php:26',
            'Docs     : https://phpmd.org/rules/unusedcode.html#unusedformalparameter',
            '</failure>',
            '      </testcase>',
            '      <testcase name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Application.php line 26" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Application.php" line="26">',
            '        <failure type="UnusedFormalParameter" message="Avoid unused parameters such as \'$input\'.">',
            'Avoid unused parameters such as \'$input\'.',
            'Rule     : Unused Code Rules / UnusedFormalParameter / Priority: 3',
            'PHP Mute : @SuppressWarnings(PHPMD.UnusedFormalParameter)',
            'File Path: /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Application.php:26',
            'Docs     : https://phpmd.org/rules/unusedcode.html#unusedformalparameter',
            '</failure>',
            '      </testcase>',
            '    </testsuite>',
            '    <testsuite name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php" tests="6" failures="6">',
            '      <testcase name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php line 24" class="Povils\PHPMND\Console" classname="Povils.PHPMND.Console" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php" line="24">',
            '        <failure type="CouplingBetweenObjects" message="The class Command has a coupling between objects value of 16. Consider to reduce the number of dependencies under 13.">',
            'The class Command has a coupling between objects value of 16. Consider to reduce the number of dependencies under 13.',
            'Rule     : Design Rules / CouplingBetweenObjects / Priority: 2',
            'PHP Mute : @SuppressWarnings(PHPMD.CouplingBetweenObjects)',
            'File Path: /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php:24',
            'Docs     : https://phpmd.org/rules/design.html#couplingbetweenobjects',
            '</failure>',
            '      </testcase>',
            '      <testcase name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php line 29" class="Povils\PHPMND\Console" classname="Povils.PHPMND.Console" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php" line="29">',
            '        <failure type="ExcessiveMethodLength" message="The method configure() has 114 lines of code. Current threshold is set to 100. Avoid really long methods.">',
            'The method configure() has 114 lines of code. Current threshold is set to 100. Avoid really long methods.',
            'Rule     : Code Size Rules / ExcessiveMethodLength / Priority: 3',
            'PHP Mute : @SuppressWarnings(PHPMD.ExcessiveMethodLength)',
            'Func     : Povils\PHPMND\Console\Command-&gt;configure()',
            'File Path: /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php:29',
            'Docs     : https://phpmd.org/rules/codesize.html#excessivemethodlength',
            '</failure>',
            '      </testcase>',
            '      <testcase name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php line 144" class="Povils\PHPMND\Console" classname="Povils.PHPMND.Console" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php" line="144">',
            '        <failure type="CyclomaticComplexity" message="The method execute() has a Cyclomatic Complexity of 15. The configured cyclomatic complexity threshold is 10.">',
            'The method execute() has a Cyclomatic Complexity of 15. The configured cyclomatic complexity threshold is 10.',
            'Rule     : Code Size Rules / CyclomaticComplexity / Priority: 3',
            'PHP Mute : @SuppressWarnings(PHPMD.CyclomaticComplexity)',
            'Func     : Povils\PHPMND\Console\Command-&gt;execute()',
            'File Path: /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php:144',
            'Docs     : https://phpmd.org/rules/codesize.html#cyclomaticcomplexity',
            '</failure>',
            '      </testcase>',
            '      <testcase name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php line 144" class="Povils\PHPMND\Console" classname="Povils.PHPMND.Console" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php" line="144">',
            '        <failure type="NPathComplexity" message="The method execute() has an NPath complexity of 2736. The configured NPath complexity threshold is 200.">',
            'The method execute() has an NPath complexity of 2736. The configured NPath complexity threshold is 200.',
            'Rule     : Code Size Rules / NPathComplexity / Priority: 3',
            'PHP Mute : @SuppressWarnings(PHPMD.NPathComplexity)',
            'Func     : Povils\PHPMND\Console\Command-&gt;execute()',
            'File Path: /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php:144',
            'Docs     : https://phpmd.org/rules/codesize.html#npathcomplexity',
            '</failure>',
            '      </testcase>',
            '      <testcase name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php line 256" class="Povils\PHPMND\Console" classname="Povils.PHPMND.Console" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php" line="256">',
            '        <failure type="UnusedPrivateMethod" message="Avoid unused private methods such as \'castToNumber\'.">',
            'Avoid unused private methods such as \'castToNumber\'.',
            'Rule     : Unused Code Rules / UnusedPrivateMethod / Priority: 3',
            'PHP Mute : @SuppressWarnings(PHPMD.UnusedPrivateMethod)',
            'Func     : Povils\PHPMND\Console\Command-&gt;castToNumber()',
            'File Path: /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php:256',
            'Docs     : https://phpmd.org/rules/unusedcode.html#unusedprivatemethod',
            '</failure>',
            '      </testcase>',
            '      <testcase name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php line 256" class="Povils\PHPMND\Console" classname="Povils.PHPMND.Console" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php" line="256">',
            '        <failure type="UnusedPrivateMethod" message="Avoid unused private methods such as \'castToNumber\'.">',
            'Avoid unused private methods such as \'castToNumber\'.',
            'Rule     : Unused Code Rules / UnusedPrivateMethod / Priority: 3',
            'PHP Mute : @SuppressWarnings(PHPMD.UnusedPrivateMethod)',
            'Func     : Povils\PHPMND\Console\Command-&gt;castToNumber()',
            'File Path: /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Command.php:256',
            'Docs     : https://phpmd.org/rules/unusedcode.html#unusedprivatemethod',
            '</failure>',
            '      </testcase>',
            '    </testsuite>',
            '    <testsuite name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Option.php" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Option.php" tests="2" failures="2">',
            '      <testcase name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Option.php line 49" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Option.php" line="49">',
            '        <failure type="LongVariable" message="Avoid excessively long variable names like $includeNumericStrings. Keep variable name length under 20.">',
            'Avoid excessively long variable names like $includeNumericStrings. Keep variable name length under 20.',
            'Rule     : Naming Rules / LongVariable / Priority: 3',
            'PHP Mute : @SuppressWarnings(PHPMD.LongVariable)',
            'File Path: /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Option.php:49',
            'Docs     : https://phpmd.org/rules/naming.html#longvariable',
            '</failure>',
            '      </testcase>',
            '      <testcase name="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Option.php line 121" file="/Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Option.php" line="121">',
            '        <failure type="LongVariable" message="Avoid excessively long variable names like $includeNumericStrings. Keep variable name length under 20.">',
            'Avoid excessively long variable names like $includeNumericStrings. Keep variable name length under 20.',
            'Rule     : Naming Rules / LongVariable / Priority: 3',
            'PHP Mute : @SuppressWarnings(PHPMD.LongVariable)',
            'File Path: /Users/smetdenis/Work/projects/jbzoo-ci-report-converter/vendor/povils/phpmnd/src/Console/Option.php:121',
            'Docs     : https://phpmd.org/rules/naming.html#longvariable',
            '</failure>',
            '      </testcase>',
            '    </testsuite>',
            '  </testsuite>',
            '</testsuites>',
            '',
        ]), $actual);
    }
}
