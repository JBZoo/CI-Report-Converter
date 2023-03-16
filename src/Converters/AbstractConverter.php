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

use JBZoo\CIReportConverter\Formats\Source\SourceSuite;

abstract class AbstractConverter
{
    public const TYPE = 'abstract';
    public const NAME = 'Abstract';

    protected ?string $rootPath = null;

    protected ?string $rootSuiteName = null;

    protected ?int $flowId = null;

    /**
     * @phan-suppress PhanUnusedPublicMethodParameter
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toInternal(string $source): SourceSuite
    {
        throw new Exception('Method \"' . __METHOD__ . '\" is not available');
    }

    /**
     * @phan-suppress PhanUnusedPublicMethodParameter
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fromInternal(SourceSuite $sourceSuite): string
    {
        throw new Exception('Method \"' . __METHOD__ . '\" is not available');
    }

    public function setRootPath(?string $rootPath): self
    {
        if ($rootPath === '.') {
            $rootPath = \realpath($rootPath);
        }

        $this->rootPath = (string)$rootPath ?: null;

        return $this;
    }

    public function setRootSuiteName(?string $rootSuiteName): self
    {
        $this->rootSuiteName = $rootSuiteName;

        return $this;
    }

    public function setFlowId(int $flowId): self
    {
        $this->flowId = $flowId;

        return $this;
    }

    protected function cleanFilepath(string $origPath): string
    {
        if ($this->rootPath && $origPath) {
            return \str_replace(\rtrim($this->rootPath, '/') . '/', '', $origPath);
        }

        return $origPath;
    }

    protected function getFullPath(?string $relFilename): ?string
    {
        if (!$relFilename) {
            return null;
        }

        if ($absFilename = \realpath($relFilename)) {
            return $absFilename;
        }

        if ($this->rootPath) {
            $rootPath    = \rtrim($this->rootPath, '/');
            $relFilename = \ltrim($relFilename, '.');
            $relFilename = \ltrim($relFilename, '/');

            if ($absFilename = \realpath($rootPath . '/' . $relFilename)) {
                return $absFilename;
            }
        }

        return $relFilename;
    }

    /**
     * @param null|int|string $line
     * @param null|int|string $column
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
