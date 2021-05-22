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

namespace JBZoo\PHPUnit;

use JBZoo\Utils\Cli;
use JBZoo\Utils\Sys;

/**
 * Class CliCommandsPharTest
 * @package JBZoo\PHPUnit
 */
class CliCommandsPharTest extends CliCommandsTest
{
    /**
     * @param string $action
     * @param array  $params
     * @return string
     * @throws \Exception
     */
    public function task(string $action, array $params = []): string
    {
        return $this->taskReal($action, $params);
    }

    /**
     * @param string $action
     * @param array  $params
     * @return string
     */
    public function taskReal(string $action, array $params = []): string
    {
        $rootDir = PROJECT_ROOT;

        return Cli::exec(
            implode(' ', [
                Sys::getBinary(),
                "{$rootDir}/build/ci-report-converter.phar --no-ansi",
                $action,
            ]),
            $params,
            $rootDir,
            false
        );
    }
}
