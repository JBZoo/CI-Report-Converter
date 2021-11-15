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

/**
 * Class Xml
 * @package JBZoo\CiReportConverter\Formats
 */
class Xml
{
    public const VERSION  = '1.0';
    public const ENCODING = 'UTF-8';

    /**
     * @param string|null $source
     * @return \DOMDocument
     */
    public static function createDomDocument(?string $source = null): \DOMDocument
    {
        $document = new \DOMDocument();
        $document->preserveWhiteSpace = false;

        if ($source) {
            $document->loadXML($source);
        }

        $document->version = self::VERSION;
        $document->encoding = self::ENCODING;
        $document->formatOutput = true;

        return $document;
    }

    /**
     * @param array             $xmlAsArray
     * @param \DOMElement|null  $domElement
     * @param \DOMDocument|null $document
     * @return \DOMDocument
     */
    public static function array2Dom(
        array $xmlAsArray,
        ?\DOMElement $domElement = null,
        ?\DOMDocument $document = null
    ): \DOMDocument {
        if (null === $document) {
            $document = self::createDomDocument();
        }

        $domElement = $domElement ?? $document;

        if ($xmlAsArray['_text'] !== null) {
            $newNode = $document->createTextNode($xmlAsArray['_text']);
            if ($newNode !== false) {
                $domElement->appendChild($newNode);
            }
        }

        if ($xmlAsArray['_cdata'] !== null) {
            $newNode = $document->createCDATASection($xmlAsArray['_cdata']);
            if ($newNode !== false) {
                $domElement->appendChild($newNode);
            }
        }

        if ($domElement instanceof \DOMElement) {
            foreach ($xmlAsArray['_attrs'] as $name => $value) {
                $domElement->setAttribute($name, $value);
            }
        }

        foreach ($xmlAsArray['_children'] as $mixedElement) {
            $newNode = $document->createElement($mixedElement['_node']);
            if ($newNode !== false) {
                $domElement->appendChild($newNode);
                self::array2Dom($mixedElement, $newNode, $document);
            }
        }

        return $document;
    }

    /**
     * @param \DOMNode|\DOMElement|\DOMDocument $element
     * @return array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public static function dom2Array(\DOMNode $element): array
    {
        $result = [
            '_node'     => $element->nodeName,
            '_text'     => null,
            '_cdata'    => null,
            '_attrs'    => [],
            '_children' => [],
        ];

        if ($element->attributes && $element->hasAttributes()) {
            foreach ($element->attributes as $attr) {
                $result['_attrs'][$attr->name] = $attr->value;
            }
        }

        if ($element->hasChildNodes()) {
            $children = $element->childNodes;

            if ($children->length === 1 && $child = $children->item(0)) {
                if ($child->nodeType === \XML_TEXT_NODE) {
                    $result['_text'] = $child->nodeValue;
                    return $result;
                }

                if ($child->nodeType === \XML_CDATA_SECTION_NODE) {
                    $result['_cdata'] = $child->nodeValue;
                    return $result;
                }
            }

            foreach ($children as $child) {
                $result['_children'][] = self::dom2Array($child);
            }
        }

        return $result;
    }
}
