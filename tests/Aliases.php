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

namespace JBZoo\PHPUnit;

use JBZoo\CIReportConverter\Formats\Xml;

class Aliases
{
    /**
     * @param string $xmlString
     */
    public static function isValidXml($xmlString, string $xsdFile = Fixtures::XSD_JUNIT): void
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

    public static function isSameXml(string $expectedCode, string $actualCode): void
    {
        $xmlExpected = Xml::createDomDocument($expectedCode);
        $xmlActual   = Xml::createDomDocument($actualCode);

        isSame($xmlExpected->saveXML(), $xmlActual->saveXML());
    }
}
