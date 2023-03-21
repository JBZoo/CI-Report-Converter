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

namespace JBZoo\PHPUnit;

use JBZoo\Utils\Cli;
use JBZoo\Utils\Sys;

final class CliCommandsPharTest extends CliCommandsTest
{
    public function task(string $action, array $params = []): string
    {
        skip('Phar tests are not implemented yet. Waiting for compiled phar');

        return $this->taskReal($action, $params);
    }

    public function taskReal(string $action, array $params = []): string
    {
        skip('Phar tests are not implemented yet. Waiting for compiled phar');

        $rootDir = PROJECT_ROOT;

        $params['-v'] = null;
        $params['--no-ansi'] = null;

        return Cli::exec(
            \implode(' ', [
                Sys::getBinary(),
                "{$rootDir}/build/ci-report-converter.phar",
                $action,
                '2>&1',
            ]),
            $params,
            $rootDir,
            false,
        );
    }
}
