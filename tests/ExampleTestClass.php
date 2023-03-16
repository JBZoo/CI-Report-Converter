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

use JBZoo\Utils\Cli;
use PHPUnit\Framework\Warning;

class ExampleTestClass extends PHPUnit
{
    protected function setUp(): void
    {
        skip("It's only for local development");
    }

    public function testValid(): void
    {
        isTrue(true);
    }

    public function testInValid(): void
    {
        isTrue(false);
    }

    public function testSkipped(): void
    {
        skip('Some reason to skip');
    }

    public function testIncomplete(): void
    {
        incomplete('Some reason to incomplete');
    }

    public function testFail(): void
    {
        fail('Some reason to fail');
    }

    public function testEcho(): void
    {
        echo 'Some echo output';
        isTrue(1);
    }

    public function testStdOutput(): void
    {
        Cli::out('Some std output');
        isTrue(1);
    }

    public function testErrOutput(): void
    {
        Cli::err('Some err output');
        isTrue(1);
    }

    public function testNoAssert(): void
    {
    }

    public function testNotice(): void
    {
        echo $aaa;
    }

    public function testWarning(): void
    {
        throw new Warning('Some warning');
    }

    public function testException(): void
    {
        echo 'Some echo output';
        throw new Exception('Exception message');
    }

    public function testCompareArrays(): void
    {
        isSame([], [1]);
    }

    public function testCompareString(): void
    {
        isSame('132', '123');
    }
}
