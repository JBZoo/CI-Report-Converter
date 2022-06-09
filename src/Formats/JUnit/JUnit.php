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

namespace JBZoo\CiReportConverter\Formats\JUnit;

use JBZoo\CiReportConverter\Formats\Xml;

/**
 * Class JUnit
 * @package JBZoo\CiReportConverter\Formats\JUnit
 */
class JUnit
{
    /**
     * @var JUnitSuite[]
     */
    private array $testSuites = [];

    /**
     * @param string|null $name
     * @return JUnitSuite
     */
    public function addSuite(?string $name = null): JUnitSuite
    {
        $testSuite = new JUnitSuite($name);
        $this->testSuites[] = $testSuite;
        return $testSuite;
    }

    /**
     * @return \DOMDocument
     */
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

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getDom()->saveXML();
    }
}
