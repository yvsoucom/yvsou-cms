<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo   All rights reserved.
 * Author: Lican Huang
 * 
 * SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary
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
 
 
namespace App\Services;

class ShortcodeManager
{
    protected array $shortcodes = [];

    // Register a shortcode handler
    public function register(string $tag, callable $callback): void
    {
        $this->shortcodes[$tag] = $callback;
    }

    // Process content replacing shortcodes with their output
    public function process(string $content): string
    {
        if (empty($this->shortcodes)) {
            return $content;
        }

        // Regex to match [tag attr="value" ...]
        $pattern = '/\[([a-zA-Z0-9_-]+)([^\]]*)\]/';

        return preg_replace_callback($pattern, function ($matches) {
            $tag = $matches[1];
            $attrString = $matches[2] ?? '';

            if (!isset($this->shortcodes[$tag])) {
                return $matches[0]; // no handler, return original
            }

            $attrs = $this->parseAttributes($attrString);

            $callback = $this->shortcodes[$tag];

            return call_user_func($callback, $attrs);
        }, $content);
    }

    // Parse shortcode attributes key="value"
    protected function parseAttributes(string $text): array
    {
        $attrs = [];
        preg_match_all('/(\w+)="([^"]*)"/', $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $attrs[$match[1]] = $match[2];
        }
        return $attrs;
    }
}
