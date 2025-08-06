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

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    public function register()
    {
        $pluginPath = base_path('plugins');

        foreach (glob("$pluginPath/*/src/*ServiceProvider.php") as $providerPath) {
            require_once $providerPath;

            $class = $this->getClassFromPath($providerPath);

            if (class_exists($class)) {
                $this->app->register($class);
            } else {
                logger()->warning("Plugin class [$class] not found in [$providerPath]");
            }
        }
    }

    private function getClassFromPath(string $path): string
    {
        // Remove base path and normalize slashes
        $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $path);

        // Convert path to namespace
        $class = str_replace(['/', '\\'], '\\', $relativePath);
        return str_replace('.php', '', $class); // Final result
    }
}
