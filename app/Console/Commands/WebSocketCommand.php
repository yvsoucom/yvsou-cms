<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileContributor: Lican Huang
* @created 2026-01-07
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

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WebSocketCommand extends Command
{
    protected $signature = 'ws:{action} {--daemon}';
    protected $description = 'Start/Stop/Check WebSocket server';

    public function handle()
    {
        $action = $this->argument('action');
        $driver = config('websocket.driver', 'swoole');

        $basePath = base_path('ws');
        $file = $driver === 'swoole'
            ? "{$basePath}/swoole-server.php"
            : "{$basePath}/workerman-server.php";

        switch ($action) {
            case 'start':
                $cmd = "php {$file}" . ($this->option('daemon') ? ' --daemon' : '');
                $this->info("Starting {$driver} server...");
                exec($cmd, $output, $returnVar);
                $returnVar === 0
                    ? $this->info("Server started successfully.")
                    : $this->error("Failed to start server.");
                break;

            case 'stop':
                if ($driver === 'workerman') {
                    exec("php {$file} stop", $output);
                } else {
                    $this->error("Swoole requires Supervisor/systemd to stop.");
                }
                $this->info("Stop command sent.");
                break;

            case 'status':
                if ($driver === 'workerman') {
                    exec("php {$file} status", $output);
                    $this->line(implode("\n", $output));
                } else {
                    $this->info("Swoole managed via Supervisor/systemd.");
                }
                break;

            default:
                $this->error("Unknown action: {$action}");
        }
    }
}
