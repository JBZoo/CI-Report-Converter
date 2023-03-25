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

namespace JBZoo\CIReportConverter\Formats\PlainText;

use JBZoo\CIReportConverter\Formats\AbstractNode;

final class PlainTextSuite extends AbstractNode
{
    /** @var PlainTextCase[] */
    private array $testCases = [];

    public function __toString(): string
    {
        $tables = [];

        foreach ($this->testCases as $testCase) {
            if (!isset($tables[$testCase->name])) {
                $tables[$testCase->name] = new PlainTable(
                    $testCase->name !== '' ? $testCase->name : PlainText::DEFAULT_NAME,
                );
            }

            $tables[$testCase->name]->appendRow([
                ($testCase->line ?? 1) . ($testCase->column > 0 ? ":{$testCase->column}" : ''),
                $testCase->level,
                $testCase->message,
            ]);
        }

        $list = \array_reduce($tables, static function (array $acc, PlainTable $table): array {
            $acc[] = $table->render();

            return $acc;
        }, []);

        return \trim(\implode("\n", $list)) . "\n";
    }

    public function addCase(?string $name = null): PlainTextCase
    {
        $testSuite         = new PlainTextCase($name);
        $this->testCases[] = $testSuite;

        return $testSuite;
    }
}
