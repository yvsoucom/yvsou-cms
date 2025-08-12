<?php
/**
 * SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
 * SPDX-FileCopyrightText: Institute of Future Science and Technology G.K., Tokyo
 * SPDX-FileContributor: Lican Huang
 * @created 2025-08-12
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

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ShortcodeManager;

// app/Providers/ShortcodeServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ShortcodeManager;

class ShortcodeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ShortcodeManager::class, function ($app) {
            return new ShortcodeManager();
        });
    }

    public function boot()
    {
        // Example registration of core shortcodes
        $manager = $this->app->make(ShortcodeManager::class);

        // Load main app shortcodes
        $this->loadShortcodes(app_path('Shortcodes'), $manager);

        // Load plugin shortcodes

        $pluginBasePath = base_path('plugins');
        foreach (scandir($pluginBasePath) as $pluginName) {
            if ($pluginName === '.' || $pluginName === '..')
                continue;
            $pluginPath = $pluginBasePath . DIRECTORY_SEPARATOR . $pluginName;
            if (is_dir($pluginPath) && file_exists($pluginPath . DIRECTORY_SEPARATOR . 'enabled.flag')) {
                $shortcodePath = $pluginPath . DIRECTORY_SEPARATOR . 'Shortcodes';
                $this->loadShortcodes($shortcodePath, $manager);
            }
        }

    }
    /**
     * Load all shortcode PHP files from a directory and register with manager
     */
    protected function loadShortcodes(string $directory, $manager)
    {
        if (!is_dir($directory)) {
            return;
        }

        foreach (glob($directory . '/*.php') as $file) {
            $register = require $file;
            if (is_callable($register)) {
                $register($manager);
            }
        }
    }
}
