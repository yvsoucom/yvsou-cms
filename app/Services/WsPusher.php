<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileCopyrightText: Institute of Future Science and Technology G.K., Tokyo
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

namespace App\Services;

use GatewayClient\Gateway;

class WsPusher
{
    public function __construct()
    {
        // Where your register.php listens
        Gateway::$registerAddress = '127.0.0.1:1238';
    }

    public function sendToAll(array $payload): void
    {
        Gateway::sendToAll(json_encode($payload));
    }

    public function sendToUid(int $uid, array $payload): void
    {
        Gateway::sendToUid($uid, json_encode($payload));
    }

    public function sendToGroup(string $room, array $payload): void
    {
        Gateway::sendToGroup($room, json_encode($payload));
    }
}
