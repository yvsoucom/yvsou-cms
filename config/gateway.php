<?php
// SPDX-FileCopyrightText: Laravel auto generate
//
// SPDX-License-Identifier: MIT
return [
    'register_address' => env('GATEWAY_REGISTER', '127.0.0.1:1238'),

    // For Register server, split host and port
    'register_host' => env('GATEWAY_REGISTER_HOST', '0.0.0.0'),
    'register_port' => env('GATEWAY_REGISTER_PORT', 1238),
];

