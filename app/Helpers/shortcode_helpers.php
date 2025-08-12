<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileCopyrightText: Institute of Future Science and Technology G.K., Tokyo
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
 
// app/Helpers/shortcode_helpers.php

use App\Services\ShortcodeManager;
use App\Services\PluginShortcodeManager;

/**
 * Process content through app-global shortcodes.
 *
 * @param string $content
 * @param array $context Optional extra data for shortcode callbacks
 * @return string
 */
function do_shortcodes(string $content, array $context = []): string
{
    $manager = app(ShortcodeManager::class);
    return $manager->process($content, $context);
}

/**
 * Register a shortcode for app-global shortcodes.
 *
 * @param string $tag
 * @param callable $callback
 * @return void
 */
function add_shortcode(string $tag, callable $callback): void
{
    $manager = app(ShortcodeManager::class);
    $manager->register($tag, $callback);
}

/**
 * Process content through plugin-scoped shortcodes.
 *
 * @param string $pluginName
 * @param string $content
 * @param array $context Optional extra data for shortcode callbacks
 * @return string
 */
function do_plugin_shortcodes(string $pluginName, string $content, array $context = []): string
{
    $pluginManagers = app(PluginShortcodeManager::class);
    $manager = $pluginManagers->getManager($pluginName);
    return $manager->process($content, $context);
}

/**
 * Register a shortcode scoped to a plugin.
 *
 * @param string $pluginName
 * @param string $tag
 * @param callable $callback
 * @return void
 */
function add_plugin_shortcode(string $pluginName, string $tag, callable $callback): void
{
    $pluginManagers = app(PluginShortcodeManager::class);
    $manager = $pluginManagers->getManager($pluginName);
    $manager->register($tag, $callback);
}
