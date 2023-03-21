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

namespace JBZoo\CIReportConverter\Formats\JUnit;

use JBZoo\CIReportConverter\Formats\Xml;

final class JUnit
{
    /** @var JUnitSuite[] */
    private array $testSuites = [];

    public function __toString(): string
    {
        return (string)$this->getDom()->saveXML();
    }

    public function addSuite(?string $name = null): JUnitSuite
    {
        $testSuite          = new JUnitSuite($name);
        $this->testSuites[] = $testSuite;

        return $testSuite;
    }

    public function getDom(): \DOMDocument
    {
        $document = Xml::createDomDocument();

        $testSuites = new \DOMElement('testsuites');
        $document->appendChild($testSuites);

        foreach ($this->testSuites as $testSuite) {
            $testSuites->appendChild($testSuite->toXML($document));
        }

        return $document;
    }
}
