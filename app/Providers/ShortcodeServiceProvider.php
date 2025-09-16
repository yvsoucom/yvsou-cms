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



namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ShortcodeManager;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use App\Services\PluginShortcodeManager;

class ShortcodeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ShortcodeManager::class, function ($app) {
            return new ShortcodeManager();
        });

        $this->app->singleton(PluginShortcodeManager::class, function ($app) {
        return new PluginShortcodeManager();
    });
    }
    public function boot(): void
    {
        // Load global app shortcodes
        $this->loadShortcodesFrom(app_path('Shortcodes'));

        $pluginBasePath = base_path('plugins');
        if (is_dir($pluginBasePath)) {
            foreach (scandir($pluginBasePath) as $pluginName) {
                if ($pluginName === '.' || $pluginName === '..') {
                    continue;
                }
                $pluginPath = $pluginBasePath . DIRECTORY_SEPARATOR . $pluginName;
                $enabledFlagFile = $pluginPath . DIRECTORY_SEPARATOR . 'enabled.flag';

                if (is_dir($pluginPath) && file_exists($enabledFlagFile)) {
                    $shortcodeDir = $pluginPath . DIRECTORY_SEPARATOR . 'Shortcodes';
                    if (is_dir($shortcodeDir)) {
                        $pluginShortcodeManager = app(PluginShortcodeManager::class)->getManager($pluginName);
                        $this->loadShortcodesFrom($shortcodeDir, $pluginShortcodeManager);
                    }
                }
            }
        }
    }

    protected function loadShortcodesFrom(string $dir, ?ShortcodeManager $manager = null): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                // You can include shortcode registration files here.
                require $file->getRealPath();
            }
        }
    }
}