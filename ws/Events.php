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

namespace App\Ws;

use GatewayWorker\Lib\Gateway;

class Events
{
    // Called when a client connects
    public static function onConnect($client_uid)
    {
        // Ask client to auth after connect
        Gateway::sendToClient($client_uid, json_encode([
            'type' => 'hello',
            'client_id' => $client_uid,
            'next' => 'auth',
        ]));
    }

    // Called when a client sends a message
    public static function onMessage($client_uid, $message)
    {
        // Ensure JSON
        $data = json_decode($message, true);
        if (!is_array($data)) {
            Gateway::sendToClient($client_uid, json_encode([
                'type' => 'error',
                'error' => 'invalid_json'
            ]));
            return;
        }

        // Route by type
        switch ($data['type'] ?? '') {
            case 'auth':
                // Expect: {type:"auth", token:"...", groups:["groupAID","groupBID"]}
                $token  = $data['token'] ?? '';
                $groups  = $data['groups'] ?? [];

                // TODO: verify token (JWT/signed) and get $uid
                // For demo we just decode a simple base64 JSON:
                // DO NOT USE IN PRODUCTION — replace with real JWT/signature verify
                $uid = self::verifyToken($token);
                if (!$uid) {
                    Gateway::sendToClient($client_uid, json_encode(['type' => 'auth', 'ok' => false]));
                    Gateway::closeClient($client_uid);
                    return;
                }

                // Bind user ID to connection
                Gateway::bindUid($client_uid, (string)$uid);

                // Join requested groups
                foreach ($groups as $group) {
                    Gateway::joinGroup($client_uid, (string)$group);
                }

                Gateway::sendToClient($client_uid, json_encode([
                    'type' => 'auth',
                    'ok' => true,
                    'uid' => $uid,
                    'groups' => $groups
                ]));

                // Notify groups that user joined
                foreach ($groups as $group) {
                    Gateway::sendToGroup((string)$group, json_encode([
                        'type' => 'presence.join',
                        'group' => $group,
                        'uid'  => $uid
                    ]));
                }
                break;

            case 'chat.send':
                // {type:"chat.send", group:"groupA", text:"hello"}
                $group = (string)($data['group'] ?? '');
                $text = (string)($data['text'] ?? '');

                if ($group === '' || $text === '') {
                    Gateway::sendToClient($client_uid, json_encode([
                        'type' => 'error',
                        'error' => 'missing_group_or_text'
                    ]));
                    return;
                }

                $uid = Gateway::getUidByClientId($client_uid);
                $msg = [
                    'type' => 'chat.message',
                    'group' => $group,
                    'from' => $uid ?: null,
                    'text' => $text,
                    'ts'   => time()
                ];

                Gateway::sendToGroup($group, json_encode($msg));
                break;

            case 'group.join':
                // {type:"group.join", group:"groupA"}
                $group = (string)($data['group'] ?? '');
                if ($group) {
                    Gateway::joinGroup($client_uid, $group);
                    $uid = Gateway::getUidByClientId($client_uid);
                    Gateway::sendToClient($client_uid, json_encode(['type'=>'group.joined','group'=>$group]));
                    Gateway::sendToGroup($group, json_encode(['type'=>'presence.join','group'=>$group,'uid'=>$uid]));
                }
                break;

            case 'group.leave':
                // {type:"group.leave", group:"groupA"}
                $group = (string)($data['group'] ?? '');
                if ($group) {
                    Gateway::leaveGroup($client_uid, $group);
                    $uid = Gateway::getUidByClientId($client_uid);
                    Gateway::sendToClient($client_uid, json_encode(['type'=>'group.left','group'=>$group]));
                    Gateway::sendToGroup($group, json_encode(['type'=>'presence.leave','group'=>$group,'uid'=>$uid]));
                }
                break;

            default:
                Gateway::sendToClient($client_uid, json_encode([
                    'type' => 'error',
                    'error' => 'unknown_type'
                ]));
        }
    }

    public static function onClose($client_id)
    {
        // Optional: you can fetch uid here for logging
        // $uid = Gateway::getUidByClientId($client_id);
        // Cleanup handled by GatewayWorker
    }

    /** Replace with real JWT/signature validation */
    private static function verifyToken(string $token): ?string
    {
        // Example placeholder: token is base64 of {"uid":"123"}
        $raw = base64_decode($token, true);
        if (!$raw) return null;
        $obj = json_decode($raw, true);
        return isset($obj['uid']) ? (string)$obj['uid'] : null;
    }
}
