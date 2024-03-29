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

use JBZoo\CIReportConverter\Formats\JUnit\JUnit;
use JBZoo\CIReportConverter\Formats\JUnit\JUnitSuite;
use JBZoo\CIReportConverter\Formats\Source\SourceCaseOutput;
use JBZoo\CIReportConverter\Formats\Source\SourceSuite;
use JBZoo\CIReportConverter\Formats\Xml;

use function JBZoo\Data\data;

final class JUnitConverter extends AbstractConverter
{
    public const TYPE = 'junit';
    public const NAME = 'JUnit.xml';

    public function toInternal(string $source): SourceSuite
    {
        $xmlDocument = Xml::createDomDocument($source);
        $xmlAsArray  = Xml::dom2Array($xmlDocument);

        $testSuite = new SourceSuite($this->rootSuiteName);
        $this->createSourceNodes($xmlAsArray, $testSuite);

        return $testSuite;
    }

    public function fromInternal(SourceSuite $sourceSuite): string
    {
        $junit = new JUnit();
        $this->createJUnitNodes($sourceSuite, $junit);

        return (string)$junit;
    }

    /**
     * @return JUnit|JUnitSuite
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function createJUnitNodes(SourceSuite $source, JUnit|JUnitSuite $junitSuite)
    {
        if ($source->name !== '') {
            $junitSuite       = $junitSuite->addSuite($source->name);
            $junitSuite->file = $source->file;
        }

        foreach ($source->getSuites() as $sourceSuite) {
            $this->createJUnitNodes($sourceSuite, $junitSuite);
        }

        if ($junitSuite instanceof JUnitSuite) {
            foreach ($source->getCases() as $sourceCase) {
                $junitCase             = $junitSuite->addCase($sourceCase->name);
                $junitCase->time       = $sourceCase->time;
                $junitCase->class      = $sourceCase->class;
                $junitCase->classname  = $sourceCase->classname;
                $junitCase->file       = $sourceCase->file;
                $junitCase->line       = $sourceCase->line;
                $junitCase->assertions = $sourceCase->assertions;

                $failure = $sourceCase->failure;
                if ($failure !== null) {
                    $junitCase->addFailure($failure->type, $failure->message, $failure->details);
                }

                $warning = $sourceCase->warning;
                if ($warning !== null) {
                    $junitCase->addWarning($warning->type, $warning->message, $warning->details);
                }

                $error = $sourceCase->error;
                if ($error !== null) {
                    $junitCase->addError($error->type, $error->message, $error->details);
                }

                if ($sourceCase->stdOut !== null && $sourceCase->errOut !== null) {
                    $junitCase->addSystemOut("{$sourceCase->stdOut}\n{$sourceCase->errOut}");
                } elseif ($sourceCase->stdOut !== null && $sourceCase->errOut === null) {
                    $junitCase->addSystemOut($sourceCase->stdOut);
                } elseif ($sourceCase->errOut !== null) {
                    $junitCase->addSystemOut($sourceCase->errOut);
                }

                if ($sourceCase->skipped !== null) {
                    $junitCase->markAsSkipped();
                }
            }
        }

        return $junitSuite;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function createSourceNodes(array $xmlAsArray, SourceSuite $currentSuite): SourceSuite
    {
        $attrs = data($xmlAsArray['_attrs'] ?? []);

        if ($xmlAsArray['_node'] === 'testcase') {
            $case             = $currentSuite->addTestCase($attrs->get('name'));
            $case->time       = $attrs->getFloatNull('time');
            $case->file       = $attrs->getStringNull('file');
            $case->line       = $attrs->getIntNull('line');
            $case->class      = $attrs->getStringNull('class');
            $case->classname  = $attrs->getStringNull('classname');
            $case->assertions = $attrs->getIntNull('assertions');

            /** @var array $output */
            foreach ($xmlAsArray['_children'] as $output) {
                $typeOfOutput = $output['_node'];
                $type         = $output['_attrs']['type'] ?? null;
                $message      = $output['_attrs']['message'] ?? null;
                $details      = $output['_cdata'] ?? $output['_text'] ?? null;

                $caseOutput = new SourceCaseOutput($type, $message, $details);

                if ($typeOfOutput === 'failure') {
                    $case->failure = $caseOutput;
                } elseif ($typeOfOutput === 'error') {
                    $case->error = $caseOutput;
                } elseif ($typeOfOutput === 'warning') {
                    $case->warning = $caseOutput;
                } elseif ($typeOfOutput === 'skipped') {
                    $case->skipped = $caseOutput;
                } elseif ($typeOfOutput === 'system-out') {
                    $case->stdOut = $details;
                }
            }
        } else {
            foreach ($xmlAsArray['_children'] as $childNode) {
                $attrs = data($childNode['_attrs'] ?? []);

                if ($childNode['_node'] === 'testcase') {
                    $this->createSourceNodes($childNode, $currentSuite);
                } else {
                    $subSuite       = $currentSuite->addSuite((string)$attrs->get('name'));
                    $subSuite->file = $attrs->get('file');
                    $this->createSourceNodes($childNode, $subSuite);
                }
            }
        }

        return $currentSuite;
    }
}
