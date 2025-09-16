<?php
/**
 * SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
 
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


use Workerman\Worker;

require_once __DIR__ . '/vendor/autoload.php';

// Create WebSocket server
$ws_worker = new Worker("websocket://0.0.0.0:2346");

// Keep all connections
$ws_worker->connections = [];

// When client connects
$ws_worker->onConnect = function($connection) use ($ws_worker) {
    $connection->uid = uniqid('user_'); // assign unique ID
    $ws_worker->connections[$connection->id] = $connection;

    echo "Client joined: {$connection->getRemoteIp()} UID: {$connection->uid}\n";

    $joinMsg = "user|{$connection->uid}|:act|join";
    foreach ($ws_worker->connections as $conn) {
        $conn->send($joinMsg);
    }
};

// When message received
$ws_worker->onMessage = function($connection, $msg) use ($ws_worker) {
    foreach ($ws_worker->connections as $conn) {
        if ($conn === $connection) {
            $conn->send("user|you|:act|chat|{$msg}");
        } else {
            $conn->send("user|{$connection->uid}|:act|chat|{$msg}");
        }
    }
};

// When client disconnects
$ws_worker->onClose = function($connection) use ($ws_worker) {
    $leaveMsg = "user|{$connection->uid}|:act|left";
    foreach ($ws_worker->connections as $conn) {
        $conn->send($leaveMsg);
    }
    unset($ws_worker->connections[$connection->id]);
    echo "Client left: {$connection->getRemoteIp()} UID: {$connection->uid}\n";
};

Worker::runAll();
 