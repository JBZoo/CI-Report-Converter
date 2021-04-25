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

namespace JBZoo\ToolboxCI\Formats\JUnit;

use JBZoo\ToolboxCI\Formats\Xml;

/**
 * Class JUnit
 * @package JBZoo\ToolboxCI\Formats\JUnit
 */
class JUnit
{
    /**
     * @var JUnitSuite[]
     */
    private $testSuites = [];

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

        $testSuites = $document->createElement('testsuites');
        if ($testSuites !== false) {
            $document->appendChild($testSuites);

            foreach ($this->testSuites as $testSuite) {
                $testSuites->appendChild($testSuite->toXML($document));
            }
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
