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

namespace JBZoo\CIReportConverter\Formats\TeamCity\Writers;

use Symfony\Component\Console\Output\OutputInterface;

class SymfonyConsole implements AbstractWriter
{
    private ?OutputInterface $output = null;

    /**
     * {@inheritDoc}
     */
    public function write(?string $message): void
    {
        if ($this->output === null) {
            throw new Exception('Symfony OutputInterface endpoint is not set');
        }

        if ($message !== null) {
            $this->output->writeln($message);
        }
    }

    public function setCallback(OutputInterface $output): void
    {
        $this->output = $output;
    }
}
