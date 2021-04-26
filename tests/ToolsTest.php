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

namespace JBZoo\PHPUnit;

use JBZoo\CiReportConverter\Formats\JUnit\JUnit;
use JBZoo\CiReportConverter\Formats\Xml;
use JBZoo\CiReportConverter\Helper;

/**
 * Class ToolsTest
 * @package JBZoo\PHPUnit
 */
class ToolsTest extends PHPUnit
{
    public function testDescAsList()
    {
        $result = Helper::descAsList([
            '0'              => 'QWERTY',
            ''               => 'QWERTY123',
            'q'              => 123,
            'qwerty'         => 123,
            'qwe'            => 123,
            'qweqwerty 1234' => '',
        ]);

        isSame(implode("\n", [
            '',
            'QWERTY',
            'QWERTY123',
            'Q     : 123',
            'Qwerty: 123',
            'Qwe   : 123',
            '',
        ]), $result);
    }

    public function testCheckstyleSchema()
    {
        $xmlFiles = glob(realpath(Fixtures::ROOT) . '/**/**/checkstyle.xml');

        foreach ($xmlFiles as $junitXmlFile) {
            Aliases::isValidXml(file_get_contents($junitXmlFile), Fixtures::XSD_CHECKSTYLE);
        }
    }

    public function testPmdSchema()
    {
        $xmlFiles = glob(realpath(Fixtures::ROOT) . '/**/**/pmd.xml');

        foreach ($xmlFiles as $xmlFile) {
            Aliases::isValidXml(file_get_contents($xmlFile), Fixtures::XSD_PMD);
        }
    }

    public function testJUnitSchema()
    {
        $xmlFiles = glob(realpath(Fixtures::ROOT) . '/**/**/junit.xml');

        foreach ($xmlFiles as $xmlFile) {
            Aliases::isValidXml(file_get_contents($xmlFile));
        }
    }

    public function testFixturesExists()
    {
        $oClass = new \ReflectionClass(Fixtures::class);

        foreach ($oClass->getConstants() as $name => $path) {
            if (in_array($name, ['ROOT', 'ROOT_ORIG'], true)) {
                continue;
            }

            isTrue(realpath($path), "{$name} => {$path}");
            isFile($path, $name);
        }
    }

    public function testDom2Array()
    {
        isSame([
            '_node'     => '#document',
            '_text'     => null,
            '_cdata'    => null,
            '_attrs'    => [],
            '_children' => []
        ], Xml::dom2Array(new \DOMDocument()));

        isSame([
            '_node'     => '#document',
            '_text'     => null,
            '_cdata'    => null,
            '_attrs'    => [],
            '_children' => [
                [
                    '_node'     => 'testsuites',
                    '_text'     => null,
                    '_cdata'    => null,
                    '_attrs'    => [],
                    '_children' => []
                ]
            ]
        ], Xml::dom2Array((new JUnit())->getDom()));

        isSame([
            '_node'     => '#document',
            '_text'     => null,
            '_cdata'    => null,
            '_attrs'    => [],
            '_children' => [
                [
                    '_node'     => 'testsuites',
                    '_text'     => null,
                    '_cdata'    => null,
                    '_attrs'    => [],
                    '_children' => [
                        [
                            '_node'     => 'testsuite',
                            '_text'     => null,
                            '_cdata'    => null,
                            '_attrs'    => [
                                'name'     => 'Package #1',
                                'tests'    => '2',
                                'failures' => '1',
                            ],
                            '_children' => [
                                [
                                    '_node'     => 'testcase',
                                    '_text'     => null,
                                    '_cdata'    => null,
                                    '_attrs'    => ['name' => 'Test case 1'],
                                    '_children' =>
                                        [
                                            [
                                                '_node'     => 'failure',
                                                '_text'     => null,
                                                '_cdata'    => null,
                                                '_attrs'    => [
                                                    'type'    => 'TypeOfFailure',
                                                    'message' => 'Message',
                                                ],
                                                '_children' => [],
                                            ],
                                        ],
                                ],
                                [
                                    '_node'     => 'testcase',
                                    '_text'     => null,
                                    '_cdata'    => null,
                                    '_attrs'    => ['name' => 'Test case 2'],
                                    '_children' => [
                                        [
                                            '_node'     => 'system-out',
                                            '_text'     => 'Custom message',
                                            '_cdata'    => null,
                                            '_attrs'    => [],
                                            '_children' => [],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            '_node'     => 'testsuite',
                            '_text'     => null,
                            '_cdata'    => null,
                            '_attrs'    => ['name' => 'Package #2', 'tests' => '2', 'errors' => '1'],
                            '_children' => [
                                [
                                    '_node'     => 'testcase',
                                    '_text'     => null,
                                    '_cdata'    => null,
                                    '_attrs'    => ['name' => 'Test case 3'],
                                    '_children' => [
                                        [
                                            '_node'     => 'error',
                                            '_text'     => null,
                                            '_cdata'    => null,
                                            '_attrs'    => [
                                                'type'    => 'TypeOfError',
                                                'message' => 'Error message',
                                            ],
                                            '_children' => [],
                                        ],
                                    ],
                                ],
                                [
                                    '_node'     => 'testcase',
                                    '_text'     => null,
                                    '_cdata'    => null,
                                    '_attrs'    => ['name' => 'Test case 4'],
                                    '_children' => [
                                        [
                                            '_node'     => 'system-out',
                                            '_text'     => null,
                                            '_cdata'    => null,
                                            '_attrs'    => [],
                                            '_children' => [],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            '_node'     => 'testsuite',
                            '_text'     => null,
                            '_cdata'    => null,
                            '_attrs'    => ['name' => 'Package #3 Empty'],
                            '_children' => [],
                        ],
                    ],
                ],
            ],
        ], Xml::dom2Array($this->getXmlFixture()->getDom()));
    }

    public function testArray2Dom()
    {
        isSame((string)$this->getXmlFixture(),
            Xml::array2Dom([
                '_node'     => '#document',
                '_text'     => null,
                '_cdata'    => null,
                '_attrs'    => [],
                '_children' => [
                    [
                        '_node'     => 'testsuites',
                        '_text'     => null,
                        '_cdata'    => null,
                        '_attrs'    => [],
                        '_children' => [
                            [
                                '_node'     => 'testsuite',
                                '_text'     => null,
                                '_cdata'    => null,
                                '_attrs'    => ['name' => 'Package #1', 'tests' => '2', 'failures' => '1'],
                                '_children' => [
                                    [
                                        '_node'     => 'testcase',
                                        '_text'     => null,
                                        '_cdata'    => null,
                                        '_attrs'    => ['name' => 'Test case 1'],
                                        '_children' => [
                                            [
                                                '_node'     => 'failure',
                                                '_text'     => null,
                                                '_cdata'    => null,
                                                '_attrs'    => ['type' => 'TypeOfFailure', 'message' => 'Message'],
                                                '_children' => [],
                                            ],
                                        ],
                                    ],
                                    [
                                        '_node'     => 'testcase',
                                        '_text'     => null,
                                        '_cdata'    => null,
                                        '_attrs'    => ['name' => 'Test case 2'],
                                        '_children' => [
                                            [
                                                '_node'     => 'system-out',
                                                '_text'     => 'Custom message',
                                                '_cdata'    => null,
                                                '_attrs'    => [],
                                                '_children' => [],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                '_node'     => 'testsuite',
                                '_text'     => null,
                                '_cdata'    => null,
                                '_attrs'    => ['name' => 'Package #2', 'tests' => '2', 'errors' => '1'],
                                '_children' => [
                                    [
                                        '_node'     => 'testcase',
                                        '_text'     => null,
                                        '_cdata'    => null,
                                        '_attrs'    => ['name' => 'Test case 3'],
                                        '_children' => [
                                            [
                                                '_node'     => 'error',
                                                '_text'     => null,
                                                '_cdata'    => null,
                                                '_attrs'    => ['type' => 'TypeOfError', 'message' => 'Error message'],
                                                '_children' => [],
                                            ],
                                        ],
                                    ],
                                    [
                                        '_node'     => 'testcase',
                                        '_text'     => null,
                                        '_cdata'    => null,
                                        '_attrs'    => ['name' => 'Test case 4'],
                                        '_children' => [
                                            [
                                                '_node'     => 'system-out',
                                                '_text'     => null,
                                                '_cdata'    => null,
                                                '_attrs'    => [],
                                                '_children' => [],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                '_node'     => 'testsuite',
                                '_text'     => null,
                                '_cdata'    => null,
                                '_attrs'    => ['name' => 'Package #3 Empty'],
                                '_children' => [],
                            ],
                        ],
                    ],
                ],
            ])->saveXML()
        );
    }

    /**
     * @return JUnit
     */
    public function getXmlFixture()
    {
        $junit = new JUnit();
        $suite1 = $junit->addSuite('Package #1');
        $suite1->addCase('Test case 1')->addFailure('TypeOfFailure', 'Message');
        $suite1->addCase('Test case 2')->addSystemOut('Custom message');
        $suite2 = $junit->addSuite('Package #2');
        $suite2->addCase('Test case 3')->addError('TypeOfError', 'Error message');
        $suite2->addCase('Test case 4')->addSystemOut('');
        $junit->addSuite('Package #3 Empty');

        return $junit;
    }

    public function testArrayToXmlComplex()
    {
        $xmlExamples = glob(realpath(Fixtures::ROOT) . '/**/**/*.xml');

        foreach ($xmlExamples as $xmlFile) {
            $originalXml = new \DOMDocument();
            $originalXml->preserveWhiteSpace = false;
            $originalXml->loadXML(file_get_contents($xmlFile));
            $originalXml->formatOutput = true;
            $originalXml->encoding = 'UTF-8';
            $originalXml->version = '1.0';

            $actualXml = Xml::array2Dom(Xml::dom2Array($originalXml));

            isSame($originalXml->saveXML(), $actualXml->saveXML(), "File: {$xmlFile}");
        }
    }
}
