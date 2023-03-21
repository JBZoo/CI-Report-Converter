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

final class Xml
{
    public const VERSION = '1.0';
    public const ENCODING = 'UTF-8';

    public static function createDomDocument(?string $source = null): \DOMDocument
    {
        $document = new \DOMDocument();
        $document->preserveWhiteSpace = false;

        if ($source !== null) {
            $document->loadXML($source);
        }

        $document->xmlVersion = self::VERSION;
        $document->encoding = self::ENCODING;
        $document->formatOutput = true;

        return $document;
    }

    public static function array2Dom(
        array $xmlAsArray,
        ?\DOMElement $domElement = null,
        ?\DOMDocument $document = null,
    ): \DOMDocument {
        if ($document === null) {
            $document = self::createDomDocument();
        }

        $domElement ??= $document;

        if ($xmlAsArray['_text'] !== null) {
            $domElement->appendChild(new \DOMText($xmlAsArray['_text']));
        }

        if ($xmlAsArray['_cdata'] !== null) {
            $newNode = $document->createCDATASection($xmlAsArray['_cdata']);
            $domElement->appendChild($newNode);
        }

        if ($domElement instanceof \DOMElement) {
            foreach ($xmlAsArray['_attrs'] as $name => $value) {
                $domElement->setAttribute($name, $value);
            }
        }

        foreach ($xmlAsArray['_children'] as $mixedElement) {
            $newNode = $document->createElement($mixedElement['_node']);
            $domElement->appendChild($newNode);
            self::array2Dom($mixedElement, $newNode, $document);
        }

        return $document;
    }

    /**
     * @param \DOMDocument|\DOMElement|\DOMNode $element
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

        if ($element->attributes !== null && $element->hasAttributes()) {
            foreach ($element->attributes as $attr) {
                /** @var \DOMAttr $attr */
                $result['_attrs'][$attr->name] = $attr->value;
            }
        }

        if ($element->hasChildNodes()) {
            $children = $element->childNodes;

            if ($children->length === 1 && $children->item(0) !== null) {
                $child = $children->item(0);
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
