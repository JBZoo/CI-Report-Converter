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

namespace JBZoo\CiReportConverter;

use JBZoo\Utils\Str;

/**
 * Class Helper
 * @package JBZoo\CiReportConverter
 */
class Helper
{
    /**
     * @param array $data
     * @return string|null
     */
    public static function descAsList(array $data): ?string
    {
        $result = Str::listToDescription($data, true);
        if (null === $result) {
            return null;
        }

        return "\n{$result}";
    }
}
