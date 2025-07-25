<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
// SPDX-FileCopyrightText: 2025 Institute of Future Science and Technology G.K., Tokyo
// SPDX-FileContributor: Lican Huang
//
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
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
