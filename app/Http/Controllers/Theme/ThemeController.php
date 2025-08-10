<?php
/**
 * SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
 * SPDX-FileCopyrightText: Institute of Future Science and Technology G.K., Tokyo
 * SPDX-FileContributor: Lican Huang
 * @created 2025-08-11
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

// app/Http/Controllers/ThemeController.php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
 
use Illuminate\Http\Request;
 
use ZipArchive;
class ThemeController extends Controller
{
    public function index()
    {
        $themesDir = resource_path('themes');
        $themes = [];

        foreach (scandir($themesDir) as $themeFolder) {
            if ($themeFolder === '.' || $themeFolder === '..')
                continue;

            $metaFile = $themesDir . "/{$themeFolder}/theme.json";
            $meta = file_exists($metaFile) ? json_decode(file_get_contents($metaFile), true) : [];
            $meta['folder'] = $themeFolder;
            $themes[] = $meta;
        }

        return view('admin.themes.index', compact('themes'));
    }

    public function switch(Request $request)
    {
        $theme = $request->input('theme');
        // Save to config file or database
        file_put_contents(
            config_path('theme.php'),
            "<?php return ['active' => '{$theme}'];"
        );
        return back()->with('success', 'Theme switched to ' . $theme);
    }



    public function upload(Request $request)
    {
        $request->validate([
            'theme_zip' => 'required|file|mimes:zip',
        ]);

        $zipPath = $request->file('theme_zip')->getRealPath();
        $extractPath = resource_path('themes');

        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
            return back()->with('success', 'Theme uploaded and extracted.');
        } else {
            return back()->with('error', 'Failed to open zip file.');
        }
    }

}
