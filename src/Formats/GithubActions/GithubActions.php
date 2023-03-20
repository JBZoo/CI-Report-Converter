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

namespace JBZoo\CIReportConverter\Formats\GithubActions;

final class GithubActions
{
    public const DEFAULT_NAME = 'Undefined Suite Name';

    /** @var GithubCase[] */
    private array $testCases = [];

    /** @var GithubSuite[] */
    private array $testSuites = [];

    public function __toString(): string
    {
        $result = [];

        foreach ($this->testCases as $testCase) {
            $result[] = (string)$testCase;
        }

        foreach ($this->testSuites as $testSuite) {
            $result[] = '';
            $result[] = '::group::' . self::escape($testSuite->name !== '' ? $testSuite->name : self::DEFAULT_NAME);
            $result[] = (string)$testSuite;
            $result[] = "::endgroup::\n\n";
        }

        return \implode("\n", $result);
    }

    public function addCase(?string $name = null): GithubCase
    {
        $testSuite         = new GithubCase($name);
        $this->testCases[] = $testSuite;

        return $testSuite;
    }

    public function addSuite(?string $name = null): GithubSuite
    {
        $testSuite          = new GithubSuite($name ?? self::DEFAULT_NAME);
        $this->testSuites[] = $testSuite;

        return $testSuite;
    }

    public static function escape(?string $message): string
    {
        if ($message === null || $message === '') {
            return '';
        }

        return \str_replace(
            ["\n", "\r"],
            ['%0A', ''],
            \trim($message),
        );
    }
}
