<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
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


namespace App\Services;
class PluginShortcodeManager
{
    /**
     * @var ShortcodeManager[]
     */
    protected array $managers = [];

    /**
     * Get or create a ShortcodeManager instance for a plugin by name
     */
    public function getManager(string $pluginName): ShortcodeManager
    {
        if (!isset($this->managers[$pluginName])) {
            $this->managers[$pluginName] = new ShortcodeManager();
        }
        return $this->managers[$pluginName];
    }

    public function render(string $content): string
    {
        logger('debug plugin managers', $this->managers);

        if (empty($this->managers)) {
            return $content;
        }

        $pluginNames = array_map('preg_quote', array_keys($this->managers));
        logger('debug plugin pluginNames', $pluginNames);

        if (empty($pluginNames)) {
            return $content;
        }
        $pluginPattern = implode('|', $pluginNames);

        $pattern = '/
        \[
        (' . $pluginPattern . ')          # Plugin name
        :                               # Colon separator
        (\w+)                           # Shortcode name
        ([^\]]*)                        # Attributes string
        \]
        (?:([^\[]*?)\[\/\1:\2\])?      # Optional content and closing tag
    /x';

        return preg_replace_callback($pattern, function ($matches) {
            $pluginName = $matches[1];
            $shortcode = $matches[2];
            logger('debug plugin shortcode', [$pluginName, $shortcode]);
            $attrString = $matches[3] ?? '';
            $content = $matches[4] ?? '';

            if (!isset($this->managers[$pluginName])) {
                return $matches[0];
            }

            $manager = $this->managers[$pluginName];
            $attrs = $this->parseAttributes($attrString);

            if (!$manager->hasShortcode($shortcode)) {
                return $matches[0];
            }

            $handler = $manager->getShortcodeHandler($shortcode);
            return call_user_func($handler, $attrs, $content);
        }, $content);
    }


    /**
     * Simple attribute parser for shortcode attributes
     */
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