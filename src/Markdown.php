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

namespace JBZoo\ToolboxCI;

/**
 * Class Markdown
 * @package JBZoo\ToolboxCI
 */
class Markdown
{
    public const A_LEFT   = 'Left';
    public const A_CENTER = 'Center';
    public const A_RIGHT  = 'Right';

    public const CELL_MIN_LENGTH = 3;

    /**
     * @var string[]
     */
    private $headers = [];

    /**
     * @var string[]
     */
    public $alignments = [];

    /**
     * @var array
     */
    private $rows = [];

    /**
     * @param array $header
     * @param array $rows
     * @return string
     */
    public function render(array $header, array $rows): string
    {
        $this->headers = $header;
        $this->rows = $rows;

        $widths = $this->calculateWidths();

        return $this->renderHeaders($widths) . $this->renderRows($widths);
    }

    /**
     * @return int[]
     */
    protected function calculateWidths(): array
    {
        $widths = [];

        foreach (array_merge([$this->headers], $this->rows) as $row) {
            $max = count($row);

            for ($colIndex = 0; $colIndex < $max; $colIndex++) {
                $iWidth = strlen((string)$row[$colIndex]);

                if ((!array_key_exists($colIndex, $widths)) || $iWidth > $widths[$colIndex]) {
                    $widths[$colIndex] = $iWidth;
                }
            }
        }

        // all columns must be at least 3 wide for the markdown to work
        return array_map(static function (int $width) {
            return $width >= self::CELL_MIN_LENGTH ? $width : self::CELL_MIN_LENGTH;
        }, $widths);
    }

    /**
     * @param string      $title
     * @param string|null $url
     * @return string
     */
    protected static function getLink(string $title, ?string $url = null): string
    {
        return $url ? "[$title]($url)" : '';
    }

    /**
     * @param int[] $widths
     * @return string
     */
    protected function renderHeaders(array $widths): string
    {
        $result = '| ';

        foreach (array_keys($this->headers) as $colIndex) {
            $result .= self::renderCell(
                $this->headers[$colIndex],
                $this->getColumnAlign($colIndex),
                $widths[$colIndex]
            );

            $result .= ' | ';
        }

        return rtrim($result, ' ') . PHP_EOL . $this->renderAlignments($widths) . PHP_EOL;
    }

    /**
     * @param int[] $widths
     * @return string
     */
    protected function renderRows(array $widths): string
    {
        $result = '';

        foreach ($this->rows as $row) {
            $result .= '| ';

            /** @var string $colIndex */
            foreach (array_keys($row) as $colIndex) {
                $result .= self::renderCell($row[$colIndex], $this->getColumnAlign($colIndex), $widths[$colIndex]);
                $result .= ' | ';
            }

            $result = rtrim($result, ' ') . PHP_EOL;
        }

        return $result;
    }

    /**
     * @param string $contents
     * @param string $alignment
     * @param int    $width
     * @return string
     */
    protected static function renderCell(string $contents, string $alignment, int $width): string
    {
        $map = [
            self::A_LEFT   => STR_PAD_RIGHT,
            self::A_CENTER => STR_PAD_BOTH,
            self::A_RIGHT  => STR_PAD_LEFT,
        ];

        $padType = $map[$alignment] ?? STR_PAD_LEFT;

        return str_pad($contents, $width, ' ', $padType);
    }

    /**
     * @param int[] $widths
     * @return string
     */
    protected function renderAlignments(array $widths): string
    {
        $row = '|';

        foreach ($widths as $colIndex => $colIndexValue) {
            $cell = str_repeat('-', $colIndexValue + 2);
            $align = $this->getColumnAlign($colIndex);

            if ($align === self::A_CENTER) {
                $cell = ':' . substr($cell, 2) . ':';
            }

            if ($align === self::A_RIGHT) {
                $cell = substr($cell, 1) . ':';
            }

            if ($align === self::A_LEFT) {
                $cell = ':' . substr($cell, 1);
            }

            $row .= $cell . '|';
        }

        return $row;
    }

    /**
     * @param string|int $colIndex
     * @return string
     */
    protected function getColumnAlign($colIndex): string
    {
        $validAligns = [self::A_LEFT, self::A_CENTER, self::A_RIGHT];
        $result = $this->alignments[$colIndex] ?? self::A_CENTER;

        if (!in_array($result, $validAligns, true)) {
            throw new Exception("Invalid alignment for column index {$colIndex}: {$result}");
        }

        return $result;
    }
}
