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
use JBZoo\CIReportConverter\Helper;
use JBZoo\Data\Data;

use function JBZoo\Data\data;
use function JBZoo\Data\json;

class PhpMdJsonConverter extends AbstractConverter
{
    public const TYPE = 'phpmd-json';
    public const NAME = 'PHPmd.json';

    public function toInternal(string $source): SourceSuite
    {
        $sourceSuite = new SourceSuite($this->rootSuiteName ?: 'PHPmd');

        $files = (array)json($source)->get('files');

        foreach ($files as $file) {
            $relFilename = $this->cleanFilepath($file['file']);
            $absFilename = $this->getFullPath($relFilename);
            $suite       = $sourceSuite->addSuite($relFilename);
            $suite->file = $absFilename;

            foreach ($file['violations'] as $violation) {
                $violation = data($violation);
                $violation->set('full_path', $absFilename);

                $case = $suite->addTestCase("{$relFilename} line {$violation['beginLine']}");

                $case->file    = $absFilename;
                $case->line    = $violation['beginLine'] ?? null;
                $case->failure = new SourceCaseOutput(
                    $violation['rule'] ?? null,
                    $violation['description'] ?? null,
                    self::getDetails($violation),
                );

                $package = $violation['package'] ?? null;
                if ($package !== null) {
                    $case->class     = $package;
                    $case->classname = \str_replace('\\', '.', $package);
                }
            }
        }

        return $sourceSuite;
    }

    private static function getDetails(Data $data): ?string
    {
        $functionName = $data['function'] ? "{$data['function']}()" : null;
        if ($data['method']) {
            $functionName = "{$data['method']}()";
        }

        if ($data['class'] && $data['method']) {
            $functionName = "{$data['class']}->{$data['method']}()";
        }

        if ($data['class'] && $data['method'] && $data['package']) {
            $functionName = "{$data['package']}\\{$data['class']}->{$data['method']}()";
        }

        $line = (int)$data->get('beginLine');
        $line = $line > 0 ? ":{$line}" : '';

        return Helper::descAsList([
            ''          => \htmlspecialchars_decode($data->get('description')),
            'Rule'      => "{$data->get('ruleSet')} / {$data->get('rule')} / Priority: {$data->get('priority')}",
            'PHP Mute'  => "@SuppressWarnings(PHPMD.{$data->get('rule')})",
            'Func'      => $functionName ?? $data['function'],
            'File Path' => $data->get('full_path') . $line,
            'Docs'      => $data->get('externalInfoUrl'),
        ]);
    }
}
