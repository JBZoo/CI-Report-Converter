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

class CheckStyleConverter extends AbstractConverter
{
    public const TYPE = 'checkstyle';
    public const NAME = 'CheckStyle.xml';

    /**
     * {@inheritDoc}
     */
    public function toInternal(string $source): SourceSuite
    {
        $xmlDocument = Xml::createDomDocument($source);
        $xmlAsArray  = Xml::dom2Array($xmlDocument);

        $sourceSuite = new SourceSuite($this->rootSuiteName ?: 'CheckStyle');

        foreach ($xmlAsArray['_children'] as $files) {
            foreach ($files['_children'] as $file) {
                $relFilename = $this->cleanFilepath($file['_attrs']['name'] ?? 'undefined');
                $absFilename = $this->getFullPath($relFilename);

                $suite       = $sourceSuite->addSuite($relFilename);
                $suite->file = $absFilename;

                foreach ($file['_children'] as $errorNode) {
                    $error = data($errorNode['_attrs']);
                    $error->set('full_path', $absFilename);

                    $line   = $error->get('line');
                    $column = $error->get('column');
                    $type   = $error->get('source') ?? 'ERROR';

                    $caseName = $line > 0 ? "{$relFilename} line {$line}" : $relFilename;
                    $caseName = $column > 0 ? "{$caseName}, column {$column}" : $caseName;

                    $case            = $suite->addTestCase($caseName);
                    $case->file      = $absFilename;
                    $case->line      = $line ?: null;
                    $case->column    = $column ?: null;
                    $case->class     = $type;
                    $case->classname = $type;
                    $case->failure   = new SourceCaseOutput($type, $error->get('message'), self::getDetails($error));
                }
            }
        }

        return $sourceSuite;
    }

    private static function getDetails(Data $data): ?string
    {
        return Helper::descAsList([
            ''          => \htmlspecialchars_decode($data->getString('message')),
            'Rule'      => $data->get('source'),
            'File Path' => self::getFilePoint($data->get('full_path'), $data->get('line'), $data->get('column')),
            'Severity'  => $data->get('severity'),
        ]);
    }
}
