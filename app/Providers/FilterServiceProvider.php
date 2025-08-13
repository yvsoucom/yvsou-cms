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


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use App\Services\FilterManager;
use App\Services\PluginFilterManager;
class FilterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register FilterManager in Laravel's container
        $this->app->singleton(FilterManager::class, function () {
            return new FilterManager();
        });

        // Optional alias so we can call `app('filter')`
        $this->app->alias(FilterManager::class, 'filter');

        $this->app->singleton(PluginFilterManager::class, function ($app): PluginFilterManager {
            return new PluginFilterManager();
        });

    }
    protected function loadFiltersFrom(string $dir, ?FilterManager $manager = null): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                // Only require the filter file if $manager is provided or just for app filters
                require $file->getRealPath();
            }
        }
    }

    public function boot(): void
    {
        // Load app global filters
        $this->loadFiltersFrom(app_path('Filters'));

        $pluginBasePath = base_path('plugins');
        if (is_dir($pluginBasePath)) {
            foreach (scandir($pluginBasePath) as $pluginName) {
                if ($pluginName === '.' || $pluginName === '..') {
                    continue;
                }
                $pluginPath = $pluginBasePath . DIRECTORY_SEPARATOR . $pluginName;
                $enabledFlagFile = $pluginPath . DIRECTORY_SEPARATOR . 'enabled.flag';

                if (is_dir($pluginPath) && file_exists($enabledFlagFile)) {
                    // Load plugin filters only if enabled.flag exists
                    $filterDir = $pluginPath . DIRECTORY_SEPARATOR . 'Filters';
                    if (is_dir($filterDir)) {
                        // Pass plugin's FilterManager instance if needed
                        $pluginFilterManager = app(PluginFilterManager::class)->getManager($pluginName);
                        $this->loadFiltersFrom($filterDir, $pluginFilterManager);
                    }
                }
            }
        }
    }
}
