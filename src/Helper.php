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

namespace JBZoo\ToolboxCI;

use JBZoo\Utils\Str;

/**
 * Class Helper
 * @package JBZoo\ToolboxCI
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
