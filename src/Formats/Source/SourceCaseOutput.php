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

namespace JBZoo\CIReportConverter\Formats\Source;

use JBZoo\Data\Data;

use function JBZoo\Data\data;

class SourceCaseOutput
{
    public ?string $type = null;

    public ?string $message = null;

    public ?string $details = null;

    public function __construct(?string $type = null, ?string $message = null, ?string $details = null)
    {
        $this->type    = $type;
        $this->message = $message;
        $this->details = $details;
    }

    public function parseDescription(): Data
    {
        $result = [];

        $text                  = (string)$this->details;
        $result['description'] = $text;

        $lines = \explode("\n", $text);
        if (\array_key_exists(1, $lines)) {
            $result['message'] = $lines[1];
            unset($lines[0], $lines[1]);
            $result['description'] = ' ' . \ltrim(\implode("\n ", $lines));
        } else {
            $result['message']     = $lines[0];
            $result['description'] = null;
        }

        if (\strpos($text, '@@ @@') > 0) {
            $diff      = \trim(\explode('@@ @@', $text)[1]);
            $diffLines = \explode("\n", $diff);

            $actual      = [];
            $expected    = [];
            $description = [];
            $isDiffPart  = true;

            foreach ($diffLines as $diffLine) {
                $diffLine = \trim($diffLine);

                if (!$diffLine) {
                    $isDiffPart = false;
                    continue;
                }

                if ($isDiffPart) {
                    $message = \preg_replace('#^[\-\+]#', '', $diffLine);
                    if ($diffLine[0] === '-') {
                        $expected[] = $message;
                    }

                    if ($diffLine[0] === '+') {
                        $actual[] = $message;
                    }
                } else {
                    $description[] = $diffLine;
                }
            }

            $result['actual']      = \implode("\n", $actual);
            $result['expected']    = \implode("\n", $expected);
            $result['description'] = ' ' . \ltrim(\implode("\n ", $description)) . "\n ";
        }

        return data($result);
    }

    public function toArray(): array
    {
        return [
            'type'    => $this->type,
            'message' => $this->message,
            'details' => $this->details,
        ];
    }
}
