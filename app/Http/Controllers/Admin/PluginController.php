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

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class PluginController extends Controller
{
    public function index()
    {
        $plugins = collect(File::directories(base_path('plugins')))->map(function ($dir) {
            $meta = @json_decode(@file_get_contents($dir . '/plugin.json'), true);
            return [
                'name' => basename($dir),
                'enabled' => file_exists($dir . '/enabled.flag'),
                'version' => $meta['version'] ?? 'unknown',
                'dependencies' => $meta['require'] ?? [],
            ];
        });

        return view('admin.plugins.index', compact('plugins'));
    }

    public function toggle($plugin)
    {
        $flag = base_path("plugins/{$plugin}/enabled.flag");
        file_exists($flag) ? unlink($flag) : touch($flag);
        return back();
    }

    public function destroy($plugin)
    {
        File::deleteDirectory(base_path("plugins/{$plugin}"));
        return back();
    }

    public function upload(Request $request)
    {
        $request->validate(['plugin_zip' => 'required|mimes:zip']);
        $zip = new ZipArchive;
        $file = $request->file('plugin_zip');

        if ($zip->open($file->getRealPath()) === true) {
            $pluginName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $destination = base_path("plugins/{$pluginName}");
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $zip->extractTo($destination);
            $zip->close();
        }

        return back();
    }
}
