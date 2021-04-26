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

use JBZoo\Utils\Cli;
use PHPUnit\Framework\Warning;

/**
 * Class ExampleTest
 *
 * @package JBZoo\PHPUnit
 */
class ExampleTest extends PHPUnit
{
    protected function setUp(): void
    {
        skip("It's only for local development");
    }

    public function testValid()
    {
        isTrue(true);
    }

    public function testInValid()
    {
        isTrue(false);
    }

    public function testSkipped()
    {
        skip('Some reason to skip');
    }

    public function testIncomplete()
    {
        incomplete('Some reason to incomplete');
    }

    public function testFail()
    {
        fail('Some reason to fail');
    }

    public function testEcho()
    {
        echo 'Some echo output';
        isTrue(1);
    }

    public function testStdOutput()
    {
        Cli::out('Some std output');
        isTrue(1);
    }

    public function testErrOutput()
    {
        Cli::err('Some err output');
        isTrue(1);
    }

    public function testNoAssert()
    {
    }

    public function testNotice()
    {
        echo $aaa;
    }

    public function testWarning()
    {
        throw new Warning('Some warning');
    }

    public function testException()
    {
        echo 'Some echo output';
        throw new Exception('Exception message');
    }

    public function testCompareArrays()
    {
        isSame([], [1]);
    }

    public function testCompareString()
    {
        isSame('132', '123');
    }
}
