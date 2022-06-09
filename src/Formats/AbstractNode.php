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

namespace JBZoo\CiReportConverter\Formats;

use JBZoo\Data\Data;
use JBZoo\CiReportConverter\Formats\Source\Exception;
use JBZoo\CiReportConverter\Formats\Source\SourceCaseOutput;
use JBZoo\Utils\Str;

use function JBZoo\Utils\bool;
use function JBZoo\Utils\float;
use function JBZoo\Utils\int;

/**
 * Class AbstractNode
 * @package JBZoo\CiReportConverter\Formats
 *
 * @property string $name
 *
 * @method self setName(?string $name)
 */
class AbstractNode
{
    /**
     * @var string
     */
    protected string $nodeName;

    /**
     * @var Data
     */
    protected Data $data;

    /**
     * @var array
     */
    protected array $meta = [
        'name' => ['string']
    ];

    /**
     * AbstractNode constructor.
     * @param string|null $name
     */
    public function __construct(?string $name = null)
    {
        $this->data = new Data();
        $this->name = (string)$name;
        $this->nodeName = Str::getClassName(static::class);
    }

    /**
     * @param string                $name
     * @param string|float|int|null $value
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function __set(string $name, $value): void
    {
        if (!\array_key_exists($name, $this->meta)) {
            throw new Exception("Undefined property \"{$name}\"");
        }

        $isRequired = $this->meta[$name][1] ?? false;
        if ($isRequired && null === $value) {
            throw new Exception("Property \"{$name}\" can't be null");
        }

        if ($value !== null) {
            $varType = $this->meta[$name][0] ?? 'string';
            if ($varType === 'string') {
                $value = \trim((string)$value);
            } elseif ($varType === 'float') {
                $value = float($value);
            } elseif ($varType === 'int') {
                $value = int($value);
            } elseif ($varType === 'bool') {
                $value = bool($value);
            } elseif ($varType === 'array') {
                $value = (array)$value;
            }
        }

        $this->data->set($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return $this->data->offsetExists($name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->data->get($name);
    }

    /**
     * @param string $name
     * @param array  $arguments
     * @return $this|string
     */
    public function __call(string $name, array $arguments)
    {
        if (\strpos($name, 'set') === 0) {
            $name = \strtolower((string)\preg_replace("#^set#", '', $name));
            $newValue = $arguments[0] ?? null;

            if (\array_key_exists($name, $this->meta)) {
                if (null !== $newValue) {
                    $this->{$name} = $newValue;
                }

                return $this;
            }
        }

        if (\strpos($name, 'get') === 0) {
            $name = (string)\preg_replace("#^get#", '', $name);
            if (\array_key_exists($name, $this->meta)) {
                return $this->{$name};
            }
        }

        $methodName = static::class . "->{$name}()";
        throw new Exception("Undefined method \"{$methodName}\"");
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $values = $this->data->getArrayCopy();

        $result = ['_node' => $this->nodeName];

        foreach (\array_keys($this->meta) as $propName) {
            if (\array_key_exists($propName, $values) && $values[$propName] !== null) {
                if ($values[$propName] instanceof SourceCaseOutput) {
                    $result[$propName] = $values[$propName]->toArray();
                } else {
                    $result[$propName] = $values[$propName];
                }
            }
        }

        return $result;
    }
}
