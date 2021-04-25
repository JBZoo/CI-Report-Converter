<?php

/**
 * JBZoo Toolbox - Toolbox-CI
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Toolbox-CI
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Toolbox-CI
 */

declare(strict_types=1);

namespace JBZoo\ToolboxCI\Formats\GithubActions;

/**
 * Class GithubActions
 * @package JBZoo\ToolboxCI\Formats\GithubActions
 */
class GithubActions
{
    public const DEFAULT_NAME = 'Undefined Suite Name';

    /**
     * @var GithubCase[]
     */
    private $testCases = [];

    /**
     * @var GithubSuite[]
     */
    private $testSuites = [];

    /**
     * @param string|null $name
     * @return GithubCase
     */
    public function addCase(?string $name = null): GithubCase
    {
        $testSuite = new GithubCase($name);
        $this->testCases[] = $testSuite;
        return $testSuite;
    }

    /**
     * @param string|null $name
     * @return GithubSuite
     */
    public function addSuite(?string $name = null): GithubSuite
    {
        $testSuite = new GithubSuite($name ?: self::DEFAULT_NAME);
        $this->testSuites[] = $testSuite;
        return $testSuite;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $result = [];

        foreach ($this->testCases as $testCase) {
            $result[] = (string)$testCase;
        }

        foreach ($this->testSuites as $testSuite) {
            $result[] = '::group::' . self::escape($testSuite->name ?: self::DEFAULT_NAME);
            $result[] = (string)$testSuite;
            $result[] = '::endgroup::';
        }

        return implode("\n", $result);
    }

    /**
     * @param string|null $message
     * @return string
     */
    public static function escape(?string $message): string
    {
        if (null === $message || '' === $message) {
            return '';
        }

        return str_replace(
            ["\n", "\r"],
            ['%0A', ''],
            trim($message)
        );
    }
}
