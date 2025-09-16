<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd.    All rights reserved.
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

class ShortcodeManager
{
    protected $shortcodes = [];

    public function loadFromDatabase()
    {
        if (Schema::hasTable('shortcodes')) {
            $shortcodes = Shortcode::all();
            // your logic...
        }

        foreach (Shortcode::all() as $sc) {
            $this->register($sc->tag, $sc->callback);
        }
    }

    public function register($tag, $callback)
    {
        $this->shortcodes[$tag] = $callback;
    }

    public function render($content)
    {
        return preg_replace_callback('/\[(\w+)(.*?)\](?:((?:(?!\[\/\1\]).)*)\[\/\1\])?/s', function ($matches) {
            $tag = $matches[1];
            $rawAttrs = trim($matches[2]);
            $innerContent = $matches[3] ?? '';

            parse_str(str_replace(['=', '"'], ['=', '&quot;'], $rawAttrs), $attrs);
            $attrs = array_map('trim', $attrs);

            if (isset($this->shortcodes[$tag])) {
                $callback = $this->shortcodes[$tag];
                if (is_string($callback)) {
                    return eval ($callback); // ⚠️ sanitize properly or use safer design
                } elseif (is_callable($callback)) {
                    return call_user_func($callback, $attrs, $innerContent);
                }
            }

            return $matches[0]; // Return unprocessed if not found
        }, $content);
    }
}
