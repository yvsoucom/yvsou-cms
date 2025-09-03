<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileCopyrightText: Institute of Future Science and Technology G.K., Tokyo
* SPDX-FileContributor: Lican Huang
* @created 2025-08-27
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

class StartWebSocket extends Command
{
    protected $signature = 'ws:start {--d|daemon : Run servers in daemon mode}';
    protected $description = 'Start all Workerman WebSocket servers (Register, Gateway, Business)';

    public function handle()
    {
        $enabled = config('websocket.enabled', true);
        if (!$enabled) {
            $this->info('WebSocket server is disabled in config.');
            return 0;
        }

        $daemonOption = $this->option('daemon') ? ' -d' : '';

        // List of Workerman scripts
        $scripts = [
            base_path('ws/register.php'),
            base_path('ws/gateway.php'),
            base_path('ws/business.php'),
        ];

        foreach ($scripts as $script) {
            if (!file_exists($script)) {
                $this->error("File not found: {$script}");
                continue;
            }

            $this->info("Starting {$script}...");
            exec("php {$script} start{$daemonOption} 2>&1", $output, $returnVar);

            if ($returnVar === 0) {
                $this->info("✅ {$script} started successfully.");
            } else {
                $this->error("❌ Failed to start {$script}. Output:");
                $this->line(implode("\n", $output));
            }

            // Clear output for next iteration
            $output = [];
        }

        return 0;
    }
}
 