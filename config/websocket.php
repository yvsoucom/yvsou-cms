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
 
return [
    /*
    |--------------------------------------------------------------------------
    | Enable WebSocket Server
    |--------------------------------------------------------------------------
    |
    | Set to true if the user wants to run Workerman WebSocket server.
    |
    */
    'ws_enabled' => env('WEBSOCKET_ENABLED', true),

    /*
    | WebSocket host and port
    */
    'host' => env('WEBSOCKET_HOST', '0.0.0.0'),
    'port' => env('WEBSOCKET_PORT', 8080),

    /*
    | Number of worker processes
    */
    'worker_count' => env('WEBSOCKET_WORKER_COUNT', 4),
     /*
    | WebSocket driver.  swoole or workerman
    */

    'ws_driver' => env('WEBSOCKET_DRIVER', default: 'workerman'),

    
    'workerman' => [
        'gateway_register' => env('WORKERMAN_GATEWAY_REGISTER', '127.0.0.1:1238'),
        'register_host' => env('WORKERMAN_GATEWAY_REGISTER_HOST', '0.0.0.0'),
        'register_port' => env('WORKERMAN_GATEWAY_REGISTER_PORT', 1238),
    ],

    'swoole' => [
        'ssl' => env('SWOOLE_SSL', false),
        'host' => env('SWOOLE_HOST', '0.0.0.0'),
        'port' => env('SWOOLE_PORT', 9502),
        'worker_num' => env('SWOOLE_WORKER_NUM', 4),
        'task_worker_num' => env('SWOOLE_TASK_WORKER_NUM', 4),
        'max_conn' => env('SWOOLE_MAX_CONN', 10000),
        'max_request' => env('SWOOLE_MAX_REQUEST', 10000),
    ],

];

 
