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

namespace JBZoo\PHPUnit;

use JBZoo\CIReportConverter\Converters\Map;
use JBZoo\MermaidPHP\Graph;
use JBZoo\MermaidPHP\Link;
use JBZoo\MermaidPHP\Node;

final class PackageTest extends \JBZoo\Codestyle\PHPUnit\AbstractPackageTest
{
    protected string $packageName = 'CI-Report-Converter';

    protected function setUp(): void
    {
        $this->excludePaths[] = 'assets';

        $this->params['docker_pulls'] = true;

        parent::setUp();
    }

    public function testBuildGraphManually(): void
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

        $graph->addNode($toolbox = new Node('ci-report-converter', 'CI-Report<br>Converter', Node::CIRCLE));

        foreach ($sources as $sourceType => $sourceName) {
            $node = new Node($sourceType . '_src', $sourceName);
            $graph->addNode($node);
            $graph->addLink(new Link($node, $toolbox, '', Link::THICK));
        }

        foreach ($targets as $targetType => $targetName) {
            $node = new Node($targetType . '_target', $targetName);
            $graph->addNode($node);
            $graph->addLink(new Link($toolbox, $node, '', Link::THICK));
        }

        \file_put_contents(PROJECT_ROOT . '/build/directions.html', $graph->renderHtml(['version' => '8.9.2']));

        $tmpl = \implode("\n", [
            '```mermaid',
            $graph->render(),
            '```',
        ]);

        isFileContains($tmpl, PROJECT_ROOT . '/README.md');
    }
}
