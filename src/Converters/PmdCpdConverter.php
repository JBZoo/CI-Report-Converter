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

namespace JBZoo\CiReportConverter\Converters;

use JBZoo\CiReportConverter\Formats\Source\FileRef;
use JBZoo\CiReportConverter\Formats\Source\SourceCaseOutput;
use JBZoo\CiReportConverter\Formats\Source\SourceSuite;
use JBZoo\CiReportConverter\Formats\Xml;
use JBZoo\Data\Data;

use function JBZoo\Data\data;

/**
 * Class PmdCpdConverter
 * @package JBZoo\CiReportConverter\Converters
 */
class PmdCpdConverter extends AbstractConverter
{
    public const TYPE = 'pmd-cpd';
    public const NAME = 'PmdCpd.xml';

    /**
     * @inheritDoc
     */
    public function toInternal(string $source): SourceSuite
    {
        $xmlAsArray = data(Xml::dom2Array(Xml::createDomDocument($source)));

        $sourceSuite = new SourceSuite($this->rootSuiteName ?: 'PMD Copy/Paste Detector');

        foreach ($xmlAsArray->find('_children.0._children') as $duplication) {
            $duplication = data($duplication);

            $mainFile = $this->getFileByIndex($duplication);
            $errorTitle = "Duplicate code found (lines={$this->getLinesNumber($duplication)}, " .
                "tokens={$this->getTokens($duplication)})";

            $case = $sourceSuite->addTestCase($mainFile->getShortName());
            $case->file = $mainFile->fullpath;
            $case->line = $mainFile->line;
            $case->warning = new SourceCaseOutput('Warning', $errorTitle, $this->getDetails($duplication));
        }

        return $sourceSuite;
    }

    /**
     * @param Data $duplication
     * @param int  $index
     * @return FileRef
     */
    private function getFileByIndex(Data $duplication, int $index = 0): FileRef
    {
        $fileRef = new FileRef();
        $fileRef->fullpath = $duplication->findString("_children.{$index}._attrs.path");
        $fileRef->line = $duplication->findInt("_children.{$index}._attrs.line");
        $fileRef->name = $this->cleanFilepath((string)$fileRef->fullpath);

        return $fileRef;
    }

    /**
     * @param Data $duplication
     * @return FileRef[]
     */
    private function getFileList(Data $duplication): array
    {
        $list = [];

        foreach ($duplication->getArray('_children') as $index => $child) {
            if (isset($child['_node']) && $child['_node'] === 'file') {
                $list[] = $this->getFileByIndex($duplication, $index);
            }
        }

        return $list;
    }

    /**
     * @param Data $duplication
     * @return string
     */
    private function getCodeFragment(Data $duplication): string
    {
        foreach ($duplication->getArray('_children') as $child) {
            if (isset($child['_node']) && $child['_node'] === 'codefragment') {
                return $child['_text'] ?? 'Undefined Code Fragment';
            }
        }

        return 'Undefined Code Fragment';
    }

    /**
     * @param Data $duplication
     * @return int
     */
    private function getLinesNumber(Data $duplication): int
    {
        return $duplication->findInt('_attrs.lines');
    }

    /**
     * @param Data $duplication
     * @return int
     */
    private function getTokens(Data $duplication): int
    {
        return $duplication->findInt('_attrs.tokens');
    }

    /**
     * @param Data $duplication
     * @return string
     */
    private function getDetails(Data $duplication): string
    {
        $fileList = $this->getFileList($duplication);

        $filesAsString = '';
        foreach ($fileList as $fileRef) {
            $filesAsString .= "- {$fileRef->getFullName()}\n";
        }

        return implode("\n", [
            '',
            'Copy&Paste is found in files:',
            $filesAsString,
            '',
            "Code Fragment:",
            '',
            '```',
            $this->getCodeFragment($duplication),
            '```',
        ]);
    }
}
