<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd.,     All rights reserved.
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
use Symfony\Component\Process\Process;


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
        // logger("updateEnvDatabaseEngine", [$selectedEngine, $data]);
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

                    if ($engine === 'sqlite') {
                        // Ensure the database is placed under storage/sqlite/
                        $dbName = $data['DB_DATABASE'] ?? 'database.sqlite';
                        $dbPath = 'storage/sqlite/' . $dbName;

                        // Make sure directory exists
                        if (!is_dir(storage_path('sqlite'))) {
                            mkdir(storage_path('sqlite'), 0755, true);
                        }

                        // Create the SQLite file if it does not exist
                        $fullPath = storage_path('sqlite/' . $dbName);
                        logger("SQLite DB Path", [$fullPath]);
                        if (!file_exists($fullPath)) {
                            touch($fullPath);
                            chmod($fullPath, 0664);
                        }

                        $blockContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . $fullPath, $blockContent);
                    } else {
                        // MySQL / PostgreSQL updates
                        $blockContent = preg_replace('/DB_HOST=.*/', 'DB_HOST=' . ($data['DB_HOST'] ?? '127.0.0.1'), $blockContent);
                        $blockContent = preg_replace('/DB_PORT=.*/', 'DB_PORT=' . ($data['DB_PORT'] ?? ($engine === 'mysql' ? '3306' : '5432')), $blockContent);
                        $blockContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . ($data['DB_DATABASE'] ?? 'laravel'), $blockContent);
                        $blockContent = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=' . ($data['DB_USERNAME'] ?? ($engine === 'mysql' ? 'root' : 'postgres')), $blockContent);
                        $blockContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=' . ($data['DB_PASSWORD'] ?? ''), $blockContent);
                    }
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
            logger("Reloading database from .env", [""]);
            $dotenv = Dotenv::createImmutable(base_path());
            $dotenv->load();
            // Update Laravel config manually from env
            logger("Reloading database from .env - updating config", [""]);
            $connection = env('DB_CONNECTION', 'mysql');
            logger("Database connection type", [$connection]);
            Config::set("database.default", $connection);
            // Force update config from env

            $dbName = env('DB_DATABASE');
            logger("Reloading DB connection: $connection, DB: $dbName");

            switch ($connection) {
                case 'mysql':
                    Config::set('database.connections.mysql.host', env('DB_HOST', '127.0.0.1'));
                    Config::set('database.connections.mysql.port', env('DB_PORT', 3306));
                    Config::set('database.connections.mysql.database', env('DB_DATABASE', 'laravel'));
                    Config::set('database.connections.mysql.username', env('DB_USERNAME', 'root'));
                    Config::set('database.connections.mysql.password', env('DB_PASSWORD', ''));
                    break;
                case 'pgsql':
                    Config::set('database.connections.pgsql.host', env('DB_HOST', '127.0.0.1'));
                    Config::set('database.connections.pgsql.port', env('DB_PORT', 5432));
                    Config::set('database.connections.pgsql.database', env('DB_DATABASE', 'laravel'));
                    Config::set('database.connections.pgsql.username', env('DB_USERNAME', 'postgres'));
                    Config::set('database.connections.pgsql.password', env('DB_PASSWORD', ''));
                    break;
                case 'sqlite':
                    $dbPath = env('DB_DATABASE', storage_path('database.sqlite'));
                    if (!File::exists($dbPath)) {
                        File::ensureDirectoryExists(dirname($dbPath));
                        File::put($dbPath, '');
                        chmod($dbPath, 0664);
                    }
                    Config::set('database.connections.sqlite.database', $dbPath);
                    break;
            }

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
                    /*
                    $dbPath = $config['database'] ?? storage_path('database.sqlite');
                    if (!file_exists($dbPath)) {
                        touch($dbPath);
                        chmod($dbPath, 0664);
                    }
                    */
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




    public function generateAppKey()
    {
        try {
            // Clear cached config to ensure .env changes are picked up
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            // Generate the key
            Artisan::call('key:generate', [
                '--show' => false, // false to write to .env
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Application key generated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate APP_KEY: ' . $e->getMessage()
            ]);
        }
    }

    public function saveAdmin(Request $request)
    {

        $validated = $request->validate([


            'name' => 'required',
            'email' => 'required',
            'password' => 'required',

        ]);

        $this->insert_admin($request->name, $request->email, $request->password);
        return redirect(route('install.done'));
    }
    public function saveEnv(Request $request)
    {

        $validated = $request->validate([
            'app_name' => 'required',
            'app_url' => 'required',

            'db_database' => 'required',

            'db_connection' => 'required|in:mysql,pgsql,sqlite',


            //'default_lang' => 'required',
            // 'lang_set' => 'required|array|min:1', // Make sure language_set is an array and has at least one value

        ]);


        $isAdmin = $request->boolean('is_adminsp', false);
        $isBlockBot = $request->boolean('is_blockbot', false);


        logger("requestafter", [$validated]);



        #  file_put_contents(config_path('yvsou_example_config.php'), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $env = File::get(base_path('.env'));
        $defaultLang = $request->default_lang ?? 'en';
        $env = str_replace('APP_LOCALE=en', 'APP_LOCALE=' . $defaultLang, $env);

        $languages = $request->input('lang_set', []);

        // Convert to JSON string

        if (!in_array($defaultLang, $languages)) {
            $languages[] = $defaultLang;
        }

        $commaLanguages = implode(',', $languages);


        logger("commaLanguages", [$commaLanguages]);
        $env = str_replace("LANGUAGESET=en,zh,ja", "LANGUAGESET=$commaLanguages", $env);

        // logger('env', [$env]);

        $adminstring = 'false';
        if ($isAdmin)
            $adminstring = 'true';

        $env = str_replace("ADMINHASRIGHTS=true", "ADMINHASRIGHTS=$adminstring", $env);

        $blockbotstring = 'false';
        if ($isBlockBot)
            $blockbotstring = 'true';

        $env = str_replace("BLOCKBOT=false", "BLOCKBOT=$blockbotstring ", $env);



        $envData = [
            'DB_CONNECTION' => $request->db_connection,
            'DB_HOST' => $request->db_host ?? '',
            'DB_PORT' => $request->db_port ?? '',
            'DB_DATABASE' => $request->db_database,
            'DB_USERNAME' => $request->db_username ?? '',
            'DB_PASSWORD' => $request->db_password ?? '',
        ];

        $env = str_replace('APP_NAME=yvsou-cms', 'APP_NAME=' . $request->app_name, $env);
        $env = str_replace('APP_URL=http://127.0.0.1:8000', 'APP_URL=' . $request->app_url, $env);

        logger("before  put env", [""]);

        File::put(base_path('.env'), $env);
        logger("After  put env", [""]);

        $this->updateEnvDatabaseEngine($request->db_connection, $envData);
        logger("After  updateEnvDatabaseEngine", [""]);
        $this->generateAppKey();
        logger("after generateAppKey", [""]);
        $this->reloadDatabaseFromEnv();
        logger("after reloadDatabaseFromEnv", [""]);

        $this->ClearCache();
        logger("after ClearCache", [""]);
        return view('install.step2');

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


    public function showMigrate()
    {
        return view('install.migrate');
    }

    public function step3()
    {
        return view('install.step3');
    }
    public function runMigrate()
    {
        logger("🔹 Starting database migration...");

        try {
            set_time_limit(0);
            ini_set('memory_limit', '512M');

            $exitCode = \Artisan::call('migrate', [
                '--path' => 'database/migrations',
                '--force' => true,
                '--step' => true,
            ]);

            $output = \Artisan::output();
            logger("✅ Migration finished with exit code {$exitCode}:\n" . $output);
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            // THIS IS IMPORTANT
            return response()->json([
                'success' => true,
                'redirect' => route('install.step3') // must be a valid route
            ]);

        } catch (\Exception $e) {
            logger("❌ Database migration failed: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);

        }
    }
    public function ClearCache(): bool
    {
        try {
            logger("ClearCache.", [""]);
            // Clear caches
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');
            \Artisan::call('config:cache');
            \Artisan::call('route:cache');
          //  \Artisan::call('view:cache');


        } catch (\Exception $e) {
            logger("❌ ClearCache failed: " . $e->getMessage());
            return false;
        }
        logger("ClearCache complete.");
        return true;
    }



    public function done()
    {
        File::put(storage_path('installed.lock'), now());

        return view('install.done');
    }



}
