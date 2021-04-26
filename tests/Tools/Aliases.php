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

namespace JBZoo\PHPUnit;

use JBZoo\CiReportConverter\Formats\Xml;

/**
 * Class Aliases
 * @package JBZoo\PHPUnit
 */
class Aliases
{
    /**
     * @param string $xmlString
     * @param string $xsdFile
     */
    public static function isValidXml($xmlString, string $xsdFile = Fixtures::XSD_JUNIT)
    {
        isNotEmpty($xmlString);

        try {
            $xml = new \DOMDocument();
            $xml->loadXML($xmlString);
            isTrue($xml->schemaValidate($xsdFile));
        } catch (\Exception $exception) {
            fail($exception->getMessage() . "\n\n" . $xmlString);
        }
    }

    /**
     * @param string $expectedCode
     * @param string $actualCode
     */
    public static function isSameXml(string $expectedCode, string $actualCode)
    {
        $xmlExpected = Xml::createDomDocument($expectedCode);
        $xmlActual = Xml::createDomDocument($actualCode);

        isSame($xmlExpected->saveXML(), $xmlActual->saveXML());
    }
}
