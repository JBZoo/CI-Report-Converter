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

use JBZoo\ToolboxCI\Converters\Factory;
use JBZoo\ToolboxCI\Converters\Map;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Convert
 * @package JBZoo\ToolboxCI\Commands
 */
class Convert extends AbstractCommand
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $formats = 'Available options: <comment>' . implode(', ', Map::getAvailableFormats()) . '</comment>';

        $this
            ->setName('convert')
            ->setDescription('Convert one report format to another')
            // Required
            ->addOption('input-format', 'S', InputOption::VALUE_REQUIRED, "Source format. {$formats}")
            ->addOption('output-format', 'T', InputOption::VALUE_REQUIRED, "Target format. {$formats}")
            // Optional
            ->addOption('suite-name', 'N', InputOption::VALUE_REQUIRED, 'Set name of root suite');

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function executeAction(): int
    {
        $sourceCode = $this->getSourceCode();
        $sourceFormat = $this->getFormat('input-format');
        $targetFormat = $this->getFormat('output-format');

        $result = Factory::convert($sourceCode, $sourceFormat, $targetFormat, [
            'root_path'  => $this->getOption('root-path'),
            'suite_name' => $this->getOption('suite-name'),
            'flow_id'    => $this->getOption('tc-flow-id'),
        ]);

        $this->saveResult($result);

        return 0;
    }

    /**
     * @param string $optionName
     * @return string
     */
    private function getFormat(string $optionName): string
    {
        $format = strtolower(trim((string)$this->getOption($optionName)));

        $validFormats = Map::getAvailableFormats();

        if (!in_array($format, $validFormats, true)) {
            throw new Exception(
                "Format \"{$format}\" not found. See the option \"--{$optionName}\".\n" .
                "Available options: " . implode(',', $validFormats)
            );
        }

        return $format;
    }
}
