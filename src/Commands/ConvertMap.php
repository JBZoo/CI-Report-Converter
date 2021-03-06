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

namespace JBZoo\CiReportConverter\Commands;

use JBZoo\CiReportConverter\Converters\Map;
use JBZoo\Cli\Codes;

/**
 * Class ConvertMap
 * @package JBZoo\CiReportConverter\Commands
 */
class ConvertMap extends AbstractCommand
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this
            ->setName('convert:map')
            ->setDescription('Show current map of report converting');

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function executeAction(): int
    {
        $this->_(Map::getMarkdownTable());
        return Codes::OK;
    }
}
