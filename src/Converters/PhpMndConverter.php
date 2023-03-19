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

namespace JBZoo\CIReportConverter\Converters;

use JBZoo\CIReportConverter\Formats\Source\SourceCaseOutput;
use JBZoo\CIReportConverter\Formats\Source\SourceSuite;
use JBZoo\CIReportConverter\Formats\Xml;
use JBZoo\CIReportConverter\Helper;
use JBZoo\Data\Data;

use function JBZoo\Data\data;

class PhpMndConverter extends AbstractConverter
{
    public const TYPE = 'phpmnd';
    public const NAME = 'PHPmnd.xml';

    public function toInternal(string $source): SourceSuite
    {
        $xmlDocument = Xml::createDomDocument($source);
        $xmlAsArray  = Xml::dom2Array($xmlDocument);
        $files       = data($xmlAsArray)->findArray('_children.0._children.0._children');

        $sourceSuite = new SourceSuite(
            $this->rootSuiteName !== '' && $this->rootSuiteName !== null ? $this->rootSuiteName : 'PHPmnd',
        );

        foreach ($files as $file) {
            $relFilename = $this->cleanFilepath($file['_attrs']['path'] ?? 'undefined');
            $absFilename = $this->getFullPath($relFilename);

            $suite       = $sourceSuite->addSuite($relFilename);
            $suite->file = $absFilename;

            foreach ($file['_children'] as $errorNode) {
                $error = data($errorNode);
                $type  = 'Magic Number';

                $line   = $error->findInt('_attrs.line');
                $column = $error->findInt('_attrs.start');

                $caseName = $line > 0 ? "{$relFilename} line {$line}" : $relFilename;
                $caseName = $column > 0 ? "{$caseName}, column {$column}" : $caseName;

                $error->set('full_path', self::getFilePoint($absFilename, $line, $column));

                $case            = $suite->addTestCase($caseName);
                $case->file      = $absFilename;
                $case->line      = $line;
                $case->column    = $column;
                $case->class     = $type;
                $case->classname = $type;
                $case->warning   = new SourceCaseOutput($type, $error->get('message'), self::getDetails($error));
            }
        }

        return $sourceSuite;
    }

    private static function getDetails(Data $data): ?string
    {
        $snippet     = '';
        $suggestions = [];

        foreach ($data->findArray('_children') as $child) {
            if ($child['_node'] === 'snippet') {
                $snippet = '`' . \trim($data->find('_children.0._cdata')) . '`';
            }

            if ($child['_node'] === 'suggestions') {
                $suggestions = \array_reduce(
                    $data->findArray('_children.1._children'),
                    static function (array $acc, array $item): array {
                        if ($item['_text']) {
                            $acc[] = $item['_text'];
                        }

                        return $acc;
                    },
                    [],
                );
            }
        }

        return Helper::descAsList([
            'File Path'   => $data->get('full_path'),
            'Snippet'     => $snippet,
            'Suggestions' => \implode('; ', $suggestions),
        ]);
    }
}
