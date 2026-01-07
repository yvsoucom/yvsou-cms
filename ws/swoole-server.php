<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileContributor: Lican Huang
* @created 2026-01-06
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
 
// ws/swoole-server.php

use Swoole\WebSocket\Server;
use Swoole\Timer;
use Illuminate\Contracts\Console\Kernel;

require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

// Load websocket config
$wsConfig = config('websocket.swoole', [
    'host' => '0.0.0.0',
    'port' => 9502,
    'worker_num' => 4,
    'task_worker_num' => 2,
    'max_conn' => 1000,
    'max_request' => 10000,
]);

$server = new Server($wsConfig['host'], $wsConfig['port']);

$server->set([
    'worker_num' => $wsConfig['worker_num'],
    'task_worker_num' => $wsConfig['task_worker_num'],
    'max_conn' => $wsConfig['max_conn'],
    'max_request' => $wsConfig['max_request'],
    'daemonize' => in_array('--daemon', $_SERVER['argv'] ?? []),
    // native heartbeat for idle connections
    'heartbeat_idle_time' => 60,        // disconnect clients idle for 60s
    'heartbeat_check_interval' => 10,   // check every 10s
]);

/**
 * When client connects
 */
$server->on('open', function (Server $server, $request) {
    echo "Client {$request->fd} connected\n";
});

/**
 * When message received
 * JSON format: {"act":"chat","msg":"Hello"}
 */
$server->on('message', function (Server $server, $frame) {
    $data = json_decode($frame->data, true) ?: ['act' => 'chat', 'msg' => $frame->data];

    foreach ($server->connections as $fd) {
        if ($server->isEstablished($fd)) {
            $server->push($fd, json_encode([
                'user' => $frame->fd === $fd ? 'you' : $frame->fd,
                'act'  => $data['act'] ?? 'chat',
                'msg'  => $data['msg'] ?? '',
            ]));
        }
    }
});

/**
 * When client disconnects
 */
$server->on('close', function ($server, $fd) {
    echo "Client {$fd} disconnected\n";
});

/**
 * Heartbeat: send to all clients every 10s
*/
// -------------------- Heartbeat --------------------
// Use Swoole\Timer::tick() in Swoole 6+

Timer::tick(10000, function () use ($server) {
    foreach ($server->connections as $fd) {
        if ($server->isEstablished($fd)) {
            $server->push($fd, json_encode([
                'act' => 'heartbeat',
                'time' => time()
            ]));
        }
    }
});

/**
 * Start server
 */
$server->start();
 