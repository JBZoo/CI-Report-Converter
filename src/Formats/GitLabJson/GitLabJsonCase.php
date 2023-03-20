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

namespace JBZoo\CIReportConverter\Formats\GitLabJson;

use JBZoo\CIReportConverter\Formats\AbstractNode;

/**
 * @property string      $description
 * @property string      $severity
 * @property null|string $name
 * @property null|int    $line
 */
final class GitLabJsonCase extends AbstractNode
{
    public const SEVERITY_INFO     = 'info';
    public const SEVERITY_MINOR    = 'minor';
    public const SEVERITY_MAJOR    = 'major';
    public const SEVERITY_CRITICAL = 'critical';
    public const SEVERITY_BLOCKER  = 'blocker';

    public const DEFAULT_LEVEL = self::SEVERITY_MAJOR;

    public const DEFAULT_DESCRIPTION = 'Undefined error message';

    protected array $meta = [
        'description' => ['string'],
        'severity'    => ['string'],  // See self::SEVERITY_*
        'name'        => ['string'],  // It's relative path to file
        'line'        => ['int'],
    ];

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->severity    = self::DEFAULT_LEVEL;
        $this->description = self::DEFAULT_DESCRIPTION;
    }

    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'fingerprint' => \hash('sha256', \implode('', [$this->name, $this->line, $this->description])),
            'severity'    => $this->severity,
            'location'    => [
                'path'  => $this->name,
                'lines' => ['begin' => $this->line],
            ],
        ];
    }
}
