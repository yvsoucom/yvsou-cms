<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileCopyrightText: Institute of Future Science and Technology G.K., Tokyo
* SPDX-FileContributor: Lican Huang
* @created 2025-09-05
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

use App\Models\DomainMsgCenter;
use App\Helpers\WebSocket;

class MessageService
{
    public static function send(int $fromUserId, int $toUserId, string $type, array $data)
    {
        $payload = [
            'type' => $type,
            'data' => $data,
        ];

        // 1. Try realtime via WebSocket
        $ok = WebSocket::send($toUserId, $payload);

        // 2. If WebSocket fails, fallback to DB
        if (!$ok) {
            DomainMsgCenter::create([
                'from_user_id' => $fromUserId,
                'to_user_id'   => $toUserId,
                'type'         => $type,
                'data'         => json_encode($data),
            ]);
        }
    }

    public static function fetchUnread(int $userId)
    {
        return DomainMsgCenter::where('to_user_id', $userId)
            ->where('is_read', false)
            ->orderBy('created_at')
            ->get();
    }

    public static function markAsRead($messageId)
    {
        DomainMsgCenter::where('id', $messageId)->update(['is_read' => true]);
    }
}
