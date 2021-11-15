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

use JBZoo\CiReportConverter\Commands\Convert;
use JBZoo\CiReportConverter\Commands\ConvertMap;
use JBZoo\CiReportConverter\Commands\TeamCityStats;
use Symfony\Component\Console\Application;

\define('PATH_ROOT', __DIR__);

$vendorPaths = [
    __DIR__ . '/../../autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/vendor/autoload.php'
];

foreach ($vendorPaths as $file) {
    if (\file_exists($file)) {
        \define('JBZOO_AUTOLOAD_FILE', $file);
        break;
    }
}

require JBZOO_AUTOLOAD_FILE;

$application = new Application('JBZoo/CI-Report-Converter', '@git-version@');
$application->add(new Convert());
$application->add(new ConvertMap());
$application->add(new TeamCityStats());
$application->setDefaultCommand('list');
$application->run();
