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


// ws/workerman-server.php

use Workerman\Worker;
use Workerman\Connection\TcpConnection;
 
use Workerman\Timer;

require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Load websocket config
$wsConfig = config('websocket.workerman', [
    'host' => '0.0.0.0',
    'port' => 8080,
    'worker_count' => 4,
    'heartbeat_interval' => 10, // seconds
]);

$ws_worker = new Worker("websocket://{$wsConfig['host']}:{$wsConfig['port']}");

// Set number of processes
$ws_worker->count = $wsConfig['worker_count'];

// Store connections and uid mapping
$connections = []; // connection id => TcpConnection
$uids = [];        // connection id => uid

// On new connection
$ws_worker->onConnect = function (TcpConnection $connection) use (&$connections) {
    echo "Client connected: {$connection->id}\n";
    $connections[$connection->id] = $connection;
};

// On message received
$ws_worker->onMessage = function (TcpConnection $connection, $data) use (&$connections, &$uids) {
    $msgData = json_decode($data, true) ?: ['act' => 'chat', 'msg' => $data];

    // Assign uid if not already assigned
    if (!isset($uids[$connection->id])) {
        $uids[$connection->id] = $msgData['uid'] ?? $connection->id;
    }

    foreach ($connections as $conn) {
        $conn->send(json_encode([
            'user' => $conn === $connection ? 'you' : $uids[$connection->id],
            'act'  => $msgData['act'],
            'msg'  => $msgData['msg'],
        ]));
    }
};

// On connection closed
$ws_worker->onClose = function (TcpConnection $connection) use (&$connections, &$uids) {
    echo "Client disconnected: {$connection->id}\n";
    unset($connections[$connection->id], $uids[$connection->id]);
};

// Heartbeat timer
Timer::add($wsConfig['heartbeat_interval'], function () use (&$connections) {
    foreach ($connections as $connection) {
        $connection->send(json_encode([
            'act'  => 'heartbeat',
            'time' => time(),
        ]));
    }
});

// Run all workers
Worker::runAll();
 