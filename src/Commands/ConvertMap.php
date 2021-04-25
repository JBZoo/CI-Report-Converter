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

namespace JBZoo\ToolboxCI\Commands;

use JBZoo\ToolboxCI\Converters\Map;

/**
 * Class ConvertMap
 * @package JBZoo\ToolboxCI\Commands
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
    }

    /**
     * @inheritDoc
     */
    protected function executeAction(): int
    {
        $this->output->writeln(Map::getMarkdownTable());
        return 0;
    }
}
