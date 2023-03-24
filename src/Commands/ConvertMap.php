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

namespace JBZoo\CIReportConverter\Commands;

use JBZoo\CIReportConverter\Converters\Map;
use JBZoo\Cli\Codes;
use JBZoo\Markdown\Table;

final class ConvertMap extends AbstractCommand
{
    /**
     * @suppress PhanUndeclaredMethod
     */
    protected function configure(): void
    {
        $this
            ->setName('convert:map')
            ->setDescription('Show current map of report converting');

        parent::configure();
    }

    protected function executeAction(): int
    {
        $tableData = Map::getTable();
        $header    = \array_keys($tableData);

        $rows = [];

        foreach ($tableData as $key => $info) {
            $rows[$key] = \array_values(\array_map(static fn (bool $value) => $value ? 'Yes' : '-', $info));

            \array_unshift($rows[$key], $key);
        }

        \array_unshift($header, 'Source/Target');

        $output = (new Table())
            ->setHeaders($header)
            ->appendRows($rows)
            ->render();

        $this->_($output);

        return Codes::OK;
    }
}
