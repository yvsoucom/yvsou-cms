<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileCopyrightText:  
* SPDX-FileContributor: Lican Huang
* @created 2025-08-13
*
* SPDX-License-Identifier: GPL-3.0-or-later
* License: Dual Licensed – GPLv3 or Commercial
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* As an alternative to GPLv3, commercial licensing is available for organizations
* or individuals requiring proprietary usage, private modifications, or support.
*
* Contact: yvsoucom@gmail.com
* GPL License: https://www.gnu.org/licenses/gpl-3.0.html
*/


// app/Services/ShortcodeManager.php
namespace App\Services;

use App\Models\Shortcode;
use Illuminate\Support\Facades\Schema;
class ShortcodeManager
{
    protected array $shortcodes = [];


    public function register(string $tag, callable $callback): void
    {
        $this->shortcodes[$tag] = $callback;
    }

     public function hasShortcode(string $tag): bool
    {
        return isset($this->shortcodes[$tag]);
    }

    public function getShortcodeHandler(string $tag): ?callable
    {
        return $this->shortcodes[$tag] ?? null;
    }

    public function render(string $content): string
    {
        logger('debug shortcodes', $this->shortcodes);
        if (empty($this->shortcodes)) {
            return $content;
        }

        // Regex for shortcodes with optional content:
        // Matches [tag attr="val"]...[/tag] or self-closing [tag attr="val"]
        $pattern = $this->getShortcodeRegex();
        logger('debug pattern', [$pattern]);

        // Recursively parse shortcodes from inside out
        return preg_replace_callback($pattern, function ($matches) {
            $tag = $matches[2];

            if (!isset($this->shortcodes[$tag])) {
                return $matches[0]; // unknown shortcode, return as is
            }

            $attrString = $matches[3] ?? '';
            $content = $matches[5] ?? '';

            $attrs = $this->parseAttributes($attrString);

            // Call the shortcode handler with attrs and inner content
            return call_user_func($this->shortcodes[$tag], $attrs, $content);
        }, $content);


    }
    /**
     * Build regex for shortcode matching.
     */
    protected function getShortcodeRegex(): string
    {
        $tagNames = array_map('preg_quote', array_keys($this->shortcodes));
        $tagPattern = join('|', $tagNames);

        // Matches [tag attr="val"]...[/tag] or self-closing
        return '/\\[\\s*('
            . '(\\b(?:' . $tagPattern . ')\\b)' // Tag name
            . '(\\s+[^\\]]*)?'                  // Attributes
            . '\\s*(?:\\/)?\\]'                 // Closing bracket
            . '(?:([^\\[]*?)\\[\\/\\2\\])?'     // Content & closing tag
            . ')/s';

    }


    /**
     * Process content, replacing shortcodes with callback results.
     * 
     * @param string $content
     * @param array $context Optional context passed to shortcode callbacks
     * @return string
     */
    public function process(string $content, array $context = []): string
    {
        // Simple regex to find [shortcode attr="value"] patterns
        return preg_replace_callback('/\[([a-zA-Z0-9_]+)([^\]]*)\]/', function ($matches) use ($context) {
            $tag = $matches[1];
            $attrString = $matches[2];

            if (!isset($this->shortcodes[$tag])) {
                return $matches[0]; // Unknown shortcode: leave as is
            }

            // Parse attributes into array
            $attrs = $this->parseAttributes($attrString);

            // Call the shortcode callback with attributes and context
            return call_user_func($this->shortcodes[$tag], $attrs, $context);
        }, $content);
    }

    /**
     * Parse attribute string into an array.
     */
    protected function parseAttributes(string $text): array
    {
        $attrs = [];
        preg_match_all('/(\w+)\s*=\s*"([^"]*)"/', $text, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $attrs[$match[1]] = $match[2];
        }
        return $attrs;
    }
}
