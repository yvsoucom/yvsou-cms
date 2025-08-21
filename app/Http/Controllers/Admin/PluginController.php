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
use Illuminate\Support\Facades\Artisan;

class PluginController extends Controller
{
    public function index()
    {
        $activeTheme = config('theme.active');

        $plugins = collect(File::directories(base_path('plugins')))
            ->map(function ($dir) use ($activeTheme) { // pass activeTheme into closure
                $meta = @json_decode(@file_get_contents($dir . '/plugin.json'), true);

                return [
                    'name' => basename($dir),
                    'enabled' => file_exists($dir . '/enabled.flag'),
                    'activated' => ($activeTheme == basename($dir)),
                    'version' => $meta['version'] ?? 'unknown',
                    'type' => $meta['type'] ?? 'unknown',
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

    public function switch(Request $request)
    {
        $theme = $request->input('theme');
        // Save to config file or database
        file_put_contents(
            config_path('theme.php'),
            "<?php return ['active' => '{$theme}'];"
        );
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');

        return redirect()->route('admin.plugins.index')
            ->with('success', 'Theme switched to ' . $theme);
    }

    public function destroy($plugin)
    {
        File::deleteDirectory(base_path("plugins/{$plugin}"));
        return back();
    }



    function replaceMigrationPrefix(string $pluginName): void
    {
        $pluginPath = base_path("plugins/$pluginName");  // e.g., plugins/MoneyPlugin
        $prefix = strtolower($pluginName) . '_';         // e.g., moneyplugin_

        $migrationPath = $pluginPath . '/database/migrations';

        if (!File::exists($migrationPath)) {
            throw new \Exception("Migration path not found: $migrationPath");
        }

        $files = File::allFiles($migrationPath);

        foreach ($files as $file) {
            $content = File::get($file);
            if (strpos($content, '{{prefix}}') !== false) {
                $updatedContent = str_replace('{{prefix}}', $prefix, $content);
                File::put($file, $updatedContent);
            }
        }
    }

    public function upload(Request $request)
    {
        $request->validate(['plugin_zip' => 'required|mimes:zip']);
        $zip = new ZipArchive;
        $file = $request->file('plugin_zip');

        if ($zip->open($file->getRealPath()) === true) {
            $pluginName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $pluginsDir = base_path('plugins');
            $destination = "{$pluginsDir}/{$pluginName}";

            if (!file_exists($pluginsDir)) {
                mkdir($pluginsDir, 0755, true);
            }

            // Check if the ZIP already contains the folder
            $firstEntry = $zip->getNameIndex(0);
            if (substr($firstEntry, -1) === '/') {
                // ZIP has a root folder, extract into plugins/
                $zip->extractTo($pluginsDir);
            } else {
                // ZIP has files directly, create plugin folder and extract
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }
                $zip->extractTo($destination);
            }

            $zip->close();
        }
        // ✅ Clear and cache configurations, routes, and views
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('route:clear');
        Artisan::call('route:cache');
        Artisan::call('view:clear');
        Artisan::call('view:cache');

        $this->replaceMigrationPrefix($pluginName);

        Artisan::call('migrate', [
            '--path' => "plugins/{$pluginName}/database/migrations",
            '--force' => true
        ]);


        return back()->with('success', 'Plugin uploaded and caches refreshed successfully!');

    }

}
