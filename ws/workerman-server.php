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

use Workerman\Worker;
use GatewayWorker\Register;
use GatewayWorker\Gateway;
use GatewayWorker\BusinessWorker;

require_once __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$config = config('websocket');

// Register
$register = new Register(
    "text://{$config['workerman']['register_host']}:{$config['workerman']['register_port']}"
);

// Gateway
$gateway = new Gateway(
    "websocket://{$config['host']}:{$config['port']}"
);
$gateway->name = 'WebSocketGateway';
$gateway->count = $config['worker_count'];
$gateway->registerAddress = $config['workerman']['gateway_register'];

// BusinessWorker
$businessWorker = new BusinessWorker();
$businessWorker->name = 'WebSocketBusinessWorker';
$businessWorker->count = $config['worker_count'];
$businessWorker->registerAddress = $config['workerman']['gateway_register'];

Worker::runAll();
