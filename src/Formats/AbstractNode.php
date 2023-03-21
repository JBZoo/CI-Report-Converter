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

namespace JBZoo\CIReportConverter\Formats;

use JBZoo\Utils\Str;

class AbstractNode
{
    public string $name;

    protected string $nodeName;

    public function __construct(?string $name = null)
    {
        $this->name     = \trim($name ?? '');
        $this->nodeName = Str::getClassName(static::class) ?? '';
    }

    public function __call(string $name, array $arguments)
    {
        if (\str_starts_with($name, 'set')) {
            $name     = \strtolower((string)\preg_replace('#^set#', '', $name));
            $newValue = $arguments[0] ?? null;

            if ((new \ReflectionProperty($this, $name))->isPublic()) {
                $this->{$name} = $newValue;

                return $this;
            }
        }

        throw new Exception("Undefined method \"{$name}\" for class \"{$this->nodeName}\"");
    }

    public function toArray(): array
    {
        $values = \get_object_vars($this);

        $result = [
            '_node' => $this->nodeName,
        ];

        foreach ($values as $property => $value) {
            if (\is_object($value) && \method_exists($value, 'toArray')) {
                $valueAsArray = $value->toArray();
                if (\count($valueAsArray) > 0) {
                    $result[$property] = $value->toArray();
                }
            } elseif ($value !== null && (new \ReflectionProperty($this, $property))->isPublic()) {
                $result[$property] = $value;
            }
        }

        return $result;
    }
}
