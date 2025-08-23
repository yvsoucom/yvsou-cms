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


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Dotenv\Dotenv;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
class InstallController extends Controller
{
    function fixPermissions($dir)
    {
        if (!is_dir($dir))
            return;

        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..')
                continue;

            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                @chmod($path, 0775);
                $this->fixPermissions($path); // recursive
            } else {
                @chmod($path, 0664);
            }
        }
        @chmod($dir, 0775);
    }


    public function welcome()
    {

        return view('install.welcome');

    }


    public function envForm()
    {


        // Optionally clear old temp files
        array_map('unlink', glob('../storage/logs/*.log'));
        array_map('unlink', glob('../storage/framework/sessions/*'));
        array_map('unlink', glob('../storage/framework/views/*'));

        return view('install.step1');
    }





    public function saveEnv(Request $request)
    {
        logger("request", [$request->app_name]);
        logger("request", [$request->app_url]);
        logger("request", [$request->db_host]);
        logger("request", [$request->db_port]);
        logger("request", [$request->db_name]);
        logger("request", [$request->db_user]);
        logger("request", [$request->db_pass]);

        logger("request", [$request->name]);
        logger("request", [$request->email]);
        logger("request", [$request->password]);

        $validated = $request->validate([
            'app_name' => 'required',
            'app_url' => 'required',
            'db_host' => 'required',
            'db_port' => 'required',
            'db_name' => 'required',
            'db_user' => 'required',
            'db_pass' => 'nullable',

            'name' => 'required',
            'email' => 'required',
            'password' => 'required',

            'default_lang' => 'required',
            'lang_set' => 'required|array|min:1', // Make sure language_set is an array and has at least one value

        ]);

        $isAdmin = $request->boolean('is_adminsp');
        $isBlockBot = $request->boolean('is_blockbot');

        logger("requestafter", [$validated]);



        #  file_put_contents(config_path('yvsou_example_config.php'), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $cusconfig = File::get(base_path('yvsou_example_config.php'));
        $cusconfig = str_replace("'DEFAULT_LANGUAGE' => 'ja'", "'DEFAULT_LANGUAGE' => '$request->default_lang'", $cusconfig);

        $languages = $request->input('lang_set', []);

        // Convert to JSON string
        $jsonLanguages = json_encode($languages);

        $cusconfig = str_replace("'LANGUAGESET' => ['en','zh','ja']", "'LANGUAGESET' => $jsonLanguages ", $cusconfig);

        $adminstring = 'false';
        if ($isAdmin)
            $adminstring = 'true';

        $cusconfig = str_replace("'ADMINHASRIGHTS' => true", "'ADMINHASRIGHTS' =>  $adminstring ", $cusconfig);

        $blockbotstring = 'false';
        if ($isBlockBot)
            $blockbotstring = 'true';

        $cusconfig = str_replace("'BLOCKBOT' => false", "'BLOCKBOT' =>   $blockbotstring ", $cusconfig);


        File::put(config_path('yvsou_config.php'), contents: $cusconfig);
        File::put(storage_path('installed.lock'), now());



        $env = File::get(base_path('env.example'));
        $env = str_replace('APP_NAME=yvsou-cms', 'APP_NAME=' . $request->app_name, $env);
        $env = str_replace('APP_URL=http://127.0.0.1:8000', 'APP_URL=' . $request->app_url, $env);
        $env = str_replace('DB_HOST=127.0.0.1', 'DB_HOST=' . $request->db_host, $env);
        $env = str_replace('DB_PORT=3306', 'DB_PORT=' . $request->db_port, $env);
        $env = str_replace('DB_DATABASE=yvsou_test', 'DB_DATABASE=' . $request->db_name, $env);
        $env = str_replace('DB_USERNAME=root', 'DB_USERNAME=' . $request->db_user, $env);
        $env = str_replace('DB_PASSWORD=', 'DB_PASSWORD=' . $request->db_pass, $env);

        $envkeystr = 'base64:' . base64_encode(random_bytes(32));
        $env = str_replace('APP_KEY=', 'APP_KEY=' . $envkeystr, $env);



        File::put(base_path('.env'), $env);

        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');

        $this->dbmigrateCache();
        $this->insert_admin($request->name, $request->email, $request->password);

        return view('install.done');

    }

    function insert_admin($adminName, $adminEmail, $adminPassword)
    {
        \App\Models\User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => bcrypt($adminPassword),
            'role' => "admin",
        ]);

    }


    public function dbmigrateCache(): bool
    {
        // exec('composer install --no-dev --optimize-autoloader');
        try {
            \Artisan::call('migrate', [
                '--force' => true // run without confirmation in production
            ]);
        } catch (\Exception $e) {
            Log::error("❌ Post-update migrate db fail: " . $e->getMessage());

            return false;
        }
        try {

            // Clear caches
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');
            \Artisan::call('config:cache');
            \Artisan::call('route:cache');
            \Artisan::call('view:cache');
            Log::info("Post-update complete.");

        } catch (\Exception $e) {
            Log::error("❌ Post-update failed: " . $e->getMessage());
            return false;
        }

        return true;

    }





}
