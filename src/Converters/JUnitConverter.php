<?php

/**
 * JBZoo Toolbox - Toolbox-CI
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Toolbox-CI
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Toolbox-CI
 */

declare(strict_types=1);

namespace JBZoo\ToolboxCI\Converters;

use JBZoo\ToolboxCI\Formats\JUnit\JUnit;
use JBZoo\ToolboxCI\Formats\JUnit\JUnitSuite;
use JBZoo\ToolboxCI\Formats\Source\SourceCaseOutput;
use JBZoo\ToolboxCI\Formats\Source\SourceSuite;
use JBZoo\ToolboxCI\Formats\Xml;

use function JBZoo\Data\data;

/**
 * Class JUnitConverter
 * @package JBZoo\ToolboxCI\Converters
 */
class JUnitConverter extends AbstractConverter
{
    public const TYPE = 'junit';
    public const NAME = 'JUnit.xml';

    /**
     * @inheritDoc
     */
    public function toInternal(string $source): SourceSuite
    {
        $xmlDocument = Xml::createDomDocument($source);
        $xmlAsArray = Xml::dom2Array($xmlDocument);

        $testSuite = new SourceSuite($this->rootSuiteName);
        $this->createSourceNodes($xmlAsArray, $testSuite);

        return $testSuite;
    }

    /**
     * @inheritDoc
     */
    public function fromInternal(SourceSuite $sourceSuite): string
    {
        $junit = new JUnit();
        $this->createJUnitNodes($sourceSuite, $junit);
        return (string)$junit;
    }

    /**
     * @param SourceSuite      $source
     * @param JUnitSuite|JUnit $junitSuite
     * @return JUnitSuite|JUnit
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function createJUnitNodes(SourceSuite $source, $junitSuite)
    {
        if ($source->name) {
            $junitSuite = $junitSuite->addSuite($source->name);
            $junitSuite->file = $source->file;
        }

        foreach ($source->getSuites() as $sourceSuite) {
            $this->createJUnitNodes($sourceSuite, $junitSuite);
        }

        if ($junitSuite instanceof JUnitSuite) {
            foreach ($source->getCases() as $sourceCase) {
                $junitCase = $junitSuite->addCase($sourceCase->name);
                $junitCase->time = $sourceCase->time;
                $junitCase->class = $sourceCase->class;
                $junitCase->classname = $sourceCase->classname;
                $junitCase->file = $sourceCase->file;
                $junitCase->line = $sourceCase->line;
                $junitCase->assertions = $sourceCase->assertions;

                if ($failure = $sourceCase->failure) {
                    $junitCase->addFailure($failure->type, $failure->message, $failure->details);
                }

                if ($warning = $sourceCase->warning) {
                    $junitCase->addWarning($warning->type, $warning->message, $warning->details);
                }

                if ($error = $sourceCase->error) {
                    $junitCase->addError($error->type, $error->message, $error->details);
                }

                if ($sourceCase->stdOut && $sourceCase->errOut) {
                    $junitCase->addSystemOut("{$sourceCase->stdOut}\n{$sourceCase->errOut}");
                } elseif ($sourceCase->stdOut && !$sourceCase->errOut) {
                    $junitCase->addSystemOut($sourceCase->stdOut);
                } elseif ($sourceCase->errOut) {
                    $junitCase->addSystemOut($sourceCase->errOut);
                }

                if ($sourceCase->skipped) {
                    $junitCase->markAsSkipped();
                }
            }
        }

        return $junitSuite;
    }

    /**
     * @param array       $xmlAsArray
     * @param SourceSuite $currentSuite
     * @return SourceSuite
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function createSourceNodes(array $xmlAsArray, SourceSuite $currentSuite): SourceSuite
    {
        $attrs = data($xmlAsArray['_attrs'] ?? []);

        if ($xmlAsArray['_node'] === 'testcase') {
            $case = $currentSuite->addTestCase($attrs->get('name'));
            $case->time = $attrs->get('time');
            $case->file = $attrs->get('file');
            $case->line = $attrs->get('line');
            $case->class = $attrs->get('class');
            $case->classname = $attrs->get('classname');
            $case->assertions = $attrs->get('assertions');
            foreach ($xmlAsArray['_children'] as $output) {
                $typeOfOutput = $output['_node'];
                $type = $output['_attrs']['type'] ?? null;
                $message = $output['_attrs']['message'] ?? null;
                $details = ($output['_cdata'] ?? null) ?? $output['_text'] ?? null;

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
                    $subSuite = $currentSuite->addSuite((string)$attrs->get('name'));
                    $subSuite->file = $attrs->get('file');
                    $this->createSourceNodes($childNode, $subSuite);
                }
            }
        }

        return $currentSuite;
    }
}
