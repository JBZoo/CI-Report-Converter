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

use JBZoo\MermaidPHP\Graph;
use JBZoo\MermaidPHP\Helper;
use JBZoo\MermaidPHP\Link;
use JBZoo\MermaidPHP\Node;
use JBZoo\CiReportConverter\Converters\Map;

/**
 * Class CiReportConverterReadmeTest
 *
 * @package JBZoo\PHPUnit
 */
class CiReportConverterReadmeTest extends AbstractReadmeTest
{
    /**
     * @var string
     */
    protected $packageName = 'CI-Report-Converter';

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->params['strict_types'] = true;
    }

    /**
     * @return string|null
     */
    protected function checkBadgeTravis(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'Build Status',
            'https://travis-ci.org/__VENDOR_ORIG__/__PACKAGE_ORIG__.svg?branch=master',
            'https://travis-ci.org/__VENDOR_ORIG__/__PACKAGE_ORIG__'
        ));
    }

    public function testMapTable()
    {
        isFileContains(Map::getMarkdownTable(), PROJECT_ROOT . '/README.md');
    }

    public function testBuildGraphManually()
    {
        $sources = [];
        foreach (Map::MAP_TESTS as $handler => $directions) {
            if ($directions[Map::INPUT]) {
                $sources[$handler::TYPE] = $handler::NAME;
            }
        }

        $targets = [];
        foreach (Map::MAP_TESTS as $handler => $directions) {
            if ($directions[Map::OUTPUT]) {
                $targets[$handler::TYPE] = $handler::NAME;
            }
        }

        $graph = (new Graph([
            'abc_order' => true,
            'title'     => 'Direction Graph',
            'direction' => Graph::LEFT_RIGHT,
        ]))
            ->addStyle('linkStyle default interpolate basis');

        $graph->addNode($toolbox = new Node('ci-report-converter', 'CI-Report-Converter', Node::CIRCLE));

        foreach ($sources as $sourceType => $sourceName) {
            $node = new Node($sourceType . "_src", $sourceName);
            $graph->addNode($node);
            $graph->addLink(new Link($node, $toolbox, '', Link::THICK));
        }

        foreach ($targets as $targetType => $targetName) {
            $node = new Node($targetType . "_target", $targetName);
            $graph->addNode($node);
            $graph->addLink(new Link($toolbox, $node, '', Link::THICK));
        }

        file_put_contents(__DIR__ . '/../build/directions.html', $graph->renderHtml(['version' => '8.9.2']));

        $url = str_replace(
            "https://mermaid-js.github.io/mermaid-live-editor/#/edit/",
            "https://mermaid.ink/img/",
            Helper::getLiveEditorUrl($graph)
        );

        $tmpl = implode("\n", [
            '<p align="center"><!-- Auto-created image via ' . getTestName(true) . ' -->',
            "  <img src=\"{$url}\">",
            '</p>',
        ]);

        isFileContains($tmpl, PROJECT_ROOT . '/README.md');
    }
}
