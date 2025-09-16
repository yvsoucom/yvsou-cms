<?php
/**
 * SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
 * SPDX-FileContributor: Lican Huang
 * @created 2025-09-14
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

use Workerman\Worker;
use GatewayWorker\Gateway;

require __DIR__ . '/vendor/autoload.php';


// Bootstrap Laravel config system (if running inside Laravel)
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Load config
$config = config('websocket');

// If not enabled, exit gracefully
if (empty($config['enabled'])) {
    echo "WebSocket server is disabled in config/websocket.php\n";
    exit(0);
}

// Build listen string
$listen = "websocket://{$config['host']}:{$config['port']}";

// Init gateway
$gateway = new Gateway($listen);
$gateway->name = 'WebSocketGateway';
$gateway->count = $config['worker_count'];
$gateway->registerAddress =  config('gateway.register_address');  
$gateway->pingInterval = 25;
$gateway->pingNotResponseLimit = 2;
$gateway->pingData = json_encode(['type' => 'ping']);

// Run worker
Worker::runAll();

