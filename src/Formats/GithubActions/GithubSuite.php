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

namespace JBZoo\CiReportConverter\Formats\GithubActions;

use JBZoo\CiReportConverter\Formats\AbstractNode;

/**
 * Class GithubSuite
 *
 * @package JBZoo\CiReportConverter\Formats\GithubActions
 */
class GithubSuite extends AbstractNode
{
    /**
     * @var GithubCase[]
     */
    private $testCases = [];

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
     * @return string
     */
    public function __toString(): string
    {
        $result = [];
        foreach ($this->testCases as $testCase) {
            $result[] = (string)$testCase;
        }

        return implode("\n", $result);
    }
}
