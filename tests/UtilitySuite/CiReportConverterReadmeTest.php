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

use JBZoo\CiReportConverter\Converters\Map;
use JBZoo\MermaidPHP\Graph;
use JBZoo\MermaidPHP\Link;
use JBZoo\MermaidPHP\Node;

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
    protected string $packageName = 'CI-Report-Converter';

    /**
     * @var string[]
     */
    protected array $badgesTemplate = [
        'travis',
        'github_actions',
        'docker_build',
        'codecov',
        'psalm_coverage',
        'scrutinizer',
        '__BR__',
        'php_version',
        'strict_types',
        'latest_stable_version',
        'total_downloads',
        'docker_pulls',
        'github_issues',
        'github_license',
    ];

    /**
     * @return string|null
     */
    protected function checkBadgeGithubActions(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'CI',
            'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/actions/workflows/main.yml/badge.svg?branch=master',
            'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/actions/workflows/main.yml?query=branch%3Amaster'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgePhpVersion(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'PHP Version',
            'https://img.shields.io/packagist/php-v/__VENDOR__/__PACKAGE__',
            'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/blob/master/composer.json'
        ));
    }

    /**
     * @return string|null
     */
    protected function checkBadgeCodecov(): ?string
    {
        return $this->getPreparedBadge($this->getBadge(
            'codecov',
            'https://codecov.io/gh/__VENDOR_ORIG__/__PACKAGE_ORIG__/branch/master/graph/badge.svg',
            'https://codecov.io/gh/__VENDOR_ORIG__/__PACKAGE_ORIG__'
        ));
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

        $graph->addNode($toolbox = new Node('ci-report-converter', 'CI-Report<br>Converter', Node::CIRCLE));

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

        file_put_contents(PROJECT_ROOT . '/build/directions.html', $graph->renderHtml(['version' => '8.9.2']));

        $tmpl = implode("\n", [
            '```mermaid',
            $graph->render(),
            '```',
        ]);

        isFileContains($tmpl, PROJECT_ROOT . '/README.md');
    }
}
