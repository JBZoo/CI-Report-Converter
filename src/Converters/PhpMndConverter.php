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

use JBZoo\Data\Data;
use JBZoo\CiReportConverter\Formats\Source\SourceCaseOutput;
use JBZoo\CiReportConverter\Formats\Source\SourceSuite;
use JBZoo\CiReportConverter\Formats\Xml;
use JBZoo\CiReportConverter\Helper;

use function JBZoo\Data\data;

/**
 * Class PhpMndConverter
 * @package JBZoo\CiReportConverter\Converters
 */
class PhpMndConverter extends AbstractConverter
{
    public const TYPE = 'phpmnd';
    public const NAME = 'PHPmnd.xml';

    /**
     * @inheritDoc
     */
    public function toInternal(string $source): SourceSuite
    {
        $xmlDocument = Xml::createDomDocument($source);
        $xmlAsArray = Xml::dom2Array($xmlDocument);
        $files = data($xmlAsArray)->findArray('_children.0._children.0._children');

        $sourceSuite = new SourceSuite($this->rootSuiteName ?: 'PHPmnd');

        foreach ($files as $file) {
            $relFilename = $this->cleanFilepath($file['_attrs']['path'] ?? 'undefined');
            $absFilename = $this->getFullPath($relFilename);

            $suite = $sourceSuite->addSuite($relFilename);
            $suite->file = $absFilename;

            foreach ($file['_children'] as $errorNode) {
                $error = data($errorNode);
                $type = 'Magic Number';

                $line = $error->findInt('_attrs.line');
                $column = $error->findInt('_attrs.start');

                $caseName = $line > 0 ? "{$relFilename} line {$line}" : $relFilename;
                $caseName = $column > 0 ? "{$caseName}, column {$column}" : $caseName;

                $error->set('full_path', self::getFilePoint($absFilename, $line, $column));

                $case = $suite->addTestCase($caseName);
                $case->file = $absFilename;
                $case->line = $line ?: null;
                $case->column = $column ?: null;
                $case->class = $type;
                $case->classname = $type;
                $case->warning = new SourceCaseOutput($type, $error->get('message'), self::getDetails($error));
            }
        }

        return $sourceSuite;
    }

    /**
     * @param Data $data
     * @return string|null
     */
    private static function getDetails(Data $data): ?string
    {
        $snippet = '';
        $suggestions = [];

        foreach ($data->findArray('_children') as $child) {
            if ('snippet' === $child['_node']) {
                $snippet = "`" . \trim($data->find('_children.0._cdata')) . "`";
            }

            if ('suggestions' === $child['_node']) {
                $suggestions = \array_reduce(
                    $data->findArray('_children.1._children'),
                    static function (array $acc, array $item): array {
                        if ($item['_text']) {
                            $acc[] = $item['_text'];
                        }

                        return $acc;
                    },
                    []
                );
            }
        }

        return Helper::descAsList([
            'File Path'   => $data->get('full_path'),
            'Snippet'     => $snippet,
            'Suggestions' => \implode("; ", $suggestions),
        ]);
    }
}
