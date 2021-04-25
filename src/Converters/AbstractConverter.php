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

use JBZoo\ToolboxCI\Formats\Source\SourceSuite;

/**
 * Class AbstractConverter
 * @package JBZoo\ToolboxCI\Converters
 */
abstract class AbstractConverter
{
    public const TYPE = 'abstract';
    public const NAME = 'Abstract';

    /**
     * @var string|null
     */
    protected $rootPath;

    /**
     * @var string|null
     */
    protected $rootSuiteName;

    /**
     * @var int|null
     */
    protected $flowId;

    /**
     * @param string $source
     * @return SourceSuite
     * @phan-suppress PhanUnusedPublicMethodParameter
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toInternal(string $source): SourceSuite
    {
        throw new Exception('Method \"' . __METHOD__ . '\" is not available');
    }

    /**
     * @param SourceSuite $sourceSuite
     * @return string
     * @phan-suppress PhanUnusedPublicMethodParameter
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fromInternal(SourceSuite $sourceSuite): string
    {
        throw new Exception('Method \"' . __METHOD__ . '\" is not available');
    }

    /**
     * @param string|null $rootPath
     * @return $this
     */
    public function setRootPath(?string $rootPath): self
    {
        $this->rootPath = $rootPath;
        return $this;
    }

    /**
     * @param string|null $rootSuiteName
     * @return $this
     */
    public function setRootSuiteName(?string $rootSuiteName): self
    {
        $this->rootSuiteName = $rootSuiteName;
        return $this;
    }

    /**
     * @param int $flowId
     * @return $this
     */
    public function setFlowId(int $flowId): self
    {
        $this->flowId = $flowId;
        return $this;
    }

    /**
     * @param string $origPath
     * @return string
     */
    protected function cleanFilepath(string $origPath): string
    {
        if ($this->rootPath && $origPath) {
            return str_replace(rtrim($this->rootPath, '/') . '/', '', $origPath);
        }

        return $origPath;
    }

    /**
     * @param string|null $relFilename
     * @return string|null
     */
    protected function getFullPath(?string $relFilename): ?string
    {
        if (!$relFilename) {
            return null;
        }

        if ($absFilename = realpath($relFilename)) {
            return $absFilename;
        }

        if ($this->rootPath) {
            $rootPath = rtrim($this->rootPath, '/');
            $relFilename = ltrim($relFilename, '.');
            $relFilename = ltrim($relFilename, '/');

            if ($absFilename = realpath($rootPath . '/' . $relFilename)) {
                return $absFilename;
            }
        }

        return $relFilename;
    }

    /**
     * @param string|null     $filename
     * @param string|int|null $line
     * @param string|int|null $column
     * @return string|null
     */
    protected static function getFilePoint(?string $filename = null, $line = 0, $column = 0): ?string
    {
        if (!$filename) {
            return null;
        }

        $printLine = (int)$line > 1 ? ":{$line}" : '';

        if ($printLine !== '') {
            $printColumn = (int)$column > 1 ? ":{$column}" : '';
        } else {
            $printColumn = '';
        }

        return $filename . $printLine . $printColumn;
    }
}
