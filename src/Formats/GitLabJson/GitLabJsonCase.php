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

namespace JBZoo\CiReportConverter\Formats\GitLabJson;

use JBZoo\CiReportConverter\Formats\AbstractNode;

/**
 * Class GitLabJsonCase
 *
 * @property string      $description
 * @property string      $severity
 * @property string|null $name
 * @property int|null    $line
 *
 * @package JBZoo\CiReportConverter\Formats\GitLabJson
 */
class GitLabJsonCase extends AbstractNode
{
    public const SEVERITY_INFO     = 'info';
    public const SEVERITY_MINOR    = 'minor';
    public const SEVERITY_MAJOR    = 'major';
    public const SEVERITY_CRITICAL = 'critical';
    public const SEVERITY_BLOCKER  = 'blocker';

    public const DEFAULT_LEVEL = self::SEVERITY_MAJOR;

    public const DEFAULT_DESCRIPTION = 'Undefined error message';

    /**
     * @var array
     */
    protected $meta = [
        'description' => ['string'],
        'severity'    => ['string'],  // See self::SEVERITY_*
        'name'        => ['string'],  // It's relative path to file
        'line'        => ['int'],
    ];


    /**
     * GithubCase constructor.
     * @param string|null $name
     */
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->severity = self::DEFAULT_LEVEL;
        $this->description = self::DEFAULT_DESCRIPTION;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'fingerprint' => \hash('sha256', \implode([$this->name, $this->line, $this->description])),
            'severity'    => $this->severity,
            'location'    => [
                'path'  => $this->name,
                'lines' => ['begin' => $this->line]
            ]
        ];
    }
}
