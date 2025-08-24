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

    protected function updateEnvDatabaseEngine($selectedEngine, $data)
    {

        $envPath = base_path('.env');
        $content = file_get_contents($envPath);

        // Define block markers
        $blocks = [
            'mysql' => ['### MYSQL_START ###', '### MYSQL_END ###'],
            'pgsql' => ['### PGSQL_START ###', '### PGSQL_END ###'],
            'sqlite' => ['### SQLITE_START ###', '### SQLITE_END ###'],
        ];

        foreach ($blocks as $engine => [$start, $end]) {
            // Extract the block content
            $pattern = "/({$start})(.*?){$end}/s";
            if (preg_match($pattern, $content, $matches)) {
                $blockContent = $matches[2];

                if ($engine === $selectedEngine) {
                    // Uncomment and update the selected block
                    $blockContent = preg_replace('/^#\s?/m', '', $blockContent);

                    // Update values
                    $blockContent = preg_replace('/DB_HOST=.*/', 'DB_HOST=' . ($data['DB_HOST'] ?? '127.0.0.1'), $blockContent);
                    $blockContent = preg_replace('/DB_PORT=.*/', 'DB_PORT=' . ($data['DB_PORT'] ?? ($engine === 'mysql' ? '3306' : '5432')), $blockContent);
                    $blockContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . ($data['DB_DATABASE'] ?? 'laravel'), $blockContent);
                    $blockContent = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=' . ($data['DB_USERNAME'] ?? ($engine === 'mysql' ? 'root' : 'postgres')), $blockContent);
                    $blockContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=' . ($data['DB_PASSWORD'] ?? ''), $blockContent);
                } else {
                    // Comment out other blocks
                    $blockContent = preg_replace('/^(?!#)/m', '# ', $blockContent);
                }

                // Replace in content
                $content = preg_replace($pattern, "$start$blockContent$end", $content);
            }
        }

        file_put_contents($envPath, $content);
    }


    function reloadDatabaseFromEnv(): bool
    {
        try {
            // Reload .env file
            $dotenv = Dotenv::createImmutable(base_path());
            $dotenv->load();

            // Force update config from env
            Config::set('database.connections.mysql.host', env('DB_HOST'));
            Config::set('database.connections.mysql.port', env('DB_PORT'));
            Config::set('database.connections.mysql.database', env('DB_DATABASE'));
            Config::set('database.connections.mysql.username', env('DB_USERNAME'));
            Config::set('database.connections.mysql.password', env('DB_PASSWORD'));
            Config::set('database.default', env('DB_CONNECTION'));

            $connection = config('database.default', 'mysql');
            $dbName = env('DB_DATABASE');
            logger("Reloading DB connection: $connection, DB: $dbName");
            switch ($connection) {
                case 'mysql':
                    $config = Config::get("database.connections.mysql");
                    $tempConfig = $config;
                    $tempConfig['database'] = null; // connect without specifying DB

                    Config::set("database.connections.temp_mysql", $tempConfig);
                    $temp = DB::connection('temp_mysql');

                    $exists = $temp->select(
                        "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
                        [$dbName]
                    );
                    if (empty($exists)) {
                        $temp->statement("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                    }

                    DB::purge('temp_mysql');
                    break;

                case 'pgsql':
                    $config = Config::get("database.connections.pgsql");
                    $tempConfig = $config;
                    $tempConfig['database'] = 'postgres';

                    Config::set("database.connections.temp_pgsql", $tempConfig);
                    $temp = DB::connection('temp_pgsql');

                    $exists = $temp->select(
                        "SELECT 1 FROM pg_database WHERE datname = ?",
                        [$dbName]
                    );
                    if (empty($exists)) {
                        $temp->statement("CREATE DATABASE \"$dbName\"");
                    }

                    DB::purge('temp_pgsql');
                    break;

                case 'sqlite':
                    $dbPath = $config['database'] ?? database_path('database.sqlite');
                    if (!file_exists($dbPath)) {
                        touch($dbPath);
                        chmod($dbPath, 0664);
                    }
                    break;

                default:
                    throw new \Exception("Unsupported DB connection: $connection");
            }

            // Force Laravel to reconnect
            DB::purge($connection);
            DB::reconnect($connection);

            // Forget old DB instance
            app()->forgetInstance('db');

            // Bind fresh DatabaseManager
            app()->instance('db', app(\Illuminate\Database\DatabaseManager::class));

            // Clear caches to reload new connection if needed
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return true;
        } catch (\Exception $e) {
            logger('Database reload failed: ' . $e->getMessage());
            return false;
        }

    }




    public function saveEnv(Request $request)
    {

        $validated = $request->validate([
            'app_name' => 'required',
            'app_url' => 'required',
            'db_host' => 'required',
            'db_port' => 'required',
            'db_database' => 'required',
            'db_username' => 'required',
            'db_password' => 'nullable',
            'db_connection' => 'required|in:mysql,pgsql,sqlite',

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
        $cusconfig = File::get(base_path('/config/yvsou_config.php'));
        $cusconfig = str_replace("'DEFAULT_LANGUAGE' => 'en'", "'DEFAULT_LANGUAGE' => '$request->default_lang'", $cusconfig);

        $languages = $request->input('lang_set', []);

        // Convert to JSON string
        $jsonLanguages = json_encode($languages);
        logger("jsonLanguages", [$jsonLanguages]);

        $cusconfig = str_replace("'LANGUAGESET' => ['en', 'zh', 'ja']", "'LANGUAGESET' => $jsonLanguages ", $cusconfig);

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

        if ($request->db_connection === 'sqlite') {
            if (!file_exists(base_path($request->db_database))) {
                touch(base_path($request->db_database));
                chmod(base_path($request->db_database), 0664);
            }
        }

        $envData = [
            'DB_CONNECTION' => $request->db_connection,
            'DB_HOST' => $request->db_host ?? '',
            'DB_PORT' => $request->db_port ?? '',
            'DB_DATABASE' => $request->db_database,
            'DB_USERNAME' => $request->db_username ?? '',
            'DB_PASSWORD' => $request->db_password ?? '',
        ];
        $env = File::get(base_path('.env'));
        $env = str_replace('APP_NAME=yvsou-cms', 'APP_NAME=' . $request->app_name, $env);
        $env = str_replace('APP_URL=http://127.0.0.1:8000', 'APP_URL=' . $request->app_url, $env);


        $envkeystr = 'base64:' . base64_encode(random_bytes(32));
        $env = str_replace('APP_KEY=', 'APP_KEY=' . $envkeystr, $env);

        File::put(base_path('.env'), $env);

        $this->updateEnvDatabaseEngine($request->db_connection, $envData);

        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');

        $this->reloadDatabaseFromEnv();

        

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
                '--path' => 'database/migrations',
                '--force' => true
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
