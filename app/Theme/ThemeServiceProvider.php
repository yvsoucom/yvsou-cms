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


namespace App\Theme;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\View\FileViewFinder;


class ThemeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/theme.php', 'theme');


    }

    public function boot()
    {
        $activeTheme = config('theme.active');
        $themePath = base_path("plugins/{$activeTheme}");
        $themeViewPath = base_path("plugins/{$activeTheme}/views");

        $finder = View::getFinder();

        if ($finder instanceof FileViewFinder) {
            $finder->prependLocation($themeViewPath);
        } else {
            $finder->addLocation($themeViewPath);
        }



        $functions = $themePath . '/functions.php';
        if (file_exists($functions)) {
            require_once $functions;
        }

        $assetsPath = $themePath . '/assets';
        if (is_dir($assetsPath)) {
            $this->publishes([
                $assetsPath => public_path('themes/' . $activeTheme),
            ], 'themes-assets');
        }
    }
}

