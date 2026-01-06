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

use Swoole\WebSocket\Server;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../bootstrap/app.php';
$app = $config->make(Illuminate\Contracts\Console\Kernel::class);
$app->bootstrap();

$wsConfig = config('websocket.swoole');

$server = new Server(
    $wsConfig['host'],
    $wsConfig['port']
);

$server->set([
    'worker_num' => $wsConfig['worker_num'],
    'task_worker_num' => $wsConfig['task_worker_num'],
    'max_conn' => $wsConfig['max_conn'],
    'max_request' => $wsConfig['max_request'],
    'daemonize' => in_array('--daemon', $_SERVER['argv'] ?? []),
]);

$server->on('open', function (Server $server, $request) {
    echo "Client {$request->fd} connected\n";
});

$server->on('message', function (Server $server, $frame) {
    foreach ($server->connections as $fd) {
        if ($server->isEstablished($fd)) {
            $server->push($fd, json_encode([
                'user' => $frame->fd === $fd ? 'you' : $frame->fd,
                'act'  => 'chat',
                'msg'  => $frame->data,
            ]));
        }
    }
});

$server->on('close', function ($server, $fd) {
    echo "Client {$fd} closed\n";
});

$server->start();
