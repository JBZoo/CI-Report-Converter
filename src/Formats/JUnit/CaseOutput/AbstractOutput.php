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

namespace JBZoo\CIReportConverter\Formats\JUnit\CaseOutput;

use JBZoo\Utils\Xml;

abstract class AbstractOutput
{
    protected string $elementName = '';
    private ?string  $type;
    private ?string  $message;
    private ?string  $description;

    public function __construct(?string $type = null, ?string $message = null, ?string $description = null)
    {
        $this->type        = $type;
        $this->message     = $message;
        $this->description = $description;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @phan-suppress PhanPossiblyNonClassMethodCall
     * @phan-suppress PhanPossiblyFalseTypeReturn
     */
    public function toXML(\DOMDocument $document): \DOMNode
    {
        if ($this->description !== null) {
            $node = $document->createElement($this->elementName, Xml::escape($this->description));
        } else {
            $node = $document->createElement($this->elementName);
        }

        if ($this->type !== null) {
            $node->setAttribute('type', $this->type);
        }

        if ($this->message !== null) {
            $node->setAttribute('message', $this->message);
        }

        return $node;
    }
}
