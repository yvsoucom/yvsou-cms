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

use App\Helpers\FilterManager;

// app/helpers.php
if (!function_exists('do_shortcode')) {
    function do_shortcode($content)
    {
        return app('shortcode')->render($content);
    }
}

if (!function_exists('db_server_version')) {
    function db_server_version()
    {
        $driver = DB::getDriverName();

        switch ($driver) {
            case 'mysql':
            case 'mariadb':
                $sql = 'SELECT VERSION() AS version';
                break;
            case 'pgsql':
                $sql = 'SELECT version() AS version';
                break;
            case 'sqlite':
                $sql = 'SELECT sqlite_version() AS version';
                break;
            case 'sqlsrv':
                $sql = 'SELECT @@VERSION AS version';
                break;
            default:
                throw new \Exception("Unsupported driver [$driver]");
        }

        return DB::selectOne($sql)->version ?? null;
    }
}

function get_all_plugins()
{
    $pluginPath = base_path('plugins');
    $plugins = [];

    foreach (glob("$pluginPath/*/plugin.json") as $jsonPath) {
        $json = json_decode(file_get_contents($jsonPath), true);

        $slug = basename(dirname($jsonPath));
        $enabled = file_exists(dirname($jsonPath) . '/enabled.flag');

        $plugins[] = [
            'slug' => $slug,
            'name' => $json['name'] ?? ['en' => $slug],
            'shortcodes' => $json['shortcodes'] ?? [],
            'menus' => $json['menus'] ?? [],
            'enabled' => $enabled,
            'version' => $json['version'] ?? '1.0.0',
        ];
    }

    return $plugins;
}
 


 