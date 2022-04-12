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

namespace JBZoo\CiReportConverter\Formats\PlainText;

use JBZoo\CiReportConverter\Formats\AbstractNode;

/**
 * Class PlainTextSuite
 *
 * @package JBZoo\CiReportConverter\Formats\PlainText
 */
class PlainTextSuite extends AbstractNode
{
    /**
     * @var PlainTextCase[]
     */
    private $testCases = [];

    /**
     * @param string|null $name
     * @return PlainTextCase
     */
    public function addCase(?string $name = null): PlainTextCase
    {
        $testSuite = new PlainTextCase($name);
        $this->testCases[] = $testSuite;
        return $testSuite;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $tables = [];

        foreach ($this->testCases as $testCase) {
            if (!isset($tables[$testCase->name])) {
                $tables[$testCase->name] = new Table($testCase->name ?: PlainText::DEFAULT_NAME);
            }

            $tables[$testCase->name]->appendRow([
                ($testCase->line ?: 1) . ($testCase->column ? ":{$testCase->column}" : ''),
                $testCase->level,
                $testCase->message
            ]);
        }

        $list = \array_reduce($tables, static function (array $acc, Table $table): array {
            $acc[] = $table->render();
            return $acc;
        }, []);

        return \trim(\implode("\n", $list)) . "\n";
    }
}
