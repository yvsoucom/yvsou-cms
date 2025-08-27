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

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


define('LARAVEL_START', microtime(true));

$installedFlag = __DIR__ . '/../storage/installed.lock';
#$installedconfigFlag = __DIR__ . '/../config/yvsou_config.php';
$inInstaller = strpos($_SERVER['REQUEST_URI'], '/install') !== false;

if (!file_exists($installedFlag) && !$inInstaller) {
    // Check storage structure
    $dirs = [
        '../storage',
        '../storage/app',
        '../storage/framework',
        '../storage/app/private',
        '../storage/app/public',
        '../storage/app/protected-files',
        '../storage/framework/cache',
        '../storage/framework/sessions',
        '../storage/framework/testing',
        '../storage/framework/views',
        '../storage/logs',
    ];

    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true); // 0775 is more secure than 0777
        }
    }

    /*
    $filePath = __DIR__ . '/../database/database.sqlite';

    if (!file_exists($filePath)) {
        // Create an empty file
        file_put_contents($filePath, '');
    }
   */
    // Ensure vendor exists
    if (!is_dir(__DIR__ . '/../vendor')) {
        echo "Composer dependencies missing. Please run <code>composer install</code> manually.";
        exit;
    }

    // Create .env from example if not exists
    $envPath = __DIR__ . '/../.env';
    $installEnvPath = __DIR__ . '/../env.example';
    if (file_exists($installEnvPath)) {
        copy($installEnvPath, $envPath);
    }

    /*
    // Copy default config if not exists
    $configPath = __DIR__ . '/../config/yvsou_config.php';
    $installConfigPath = __DIR__ . '/../yvsou_example_config.php';
    if (file_exists($installConfigPath)) {
        copy($installConfigPath, $configPath);
    }
    */

    header('Location: install');
    exit;
}


// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}
 
// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());
