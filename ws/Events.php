<?php
namespace App\Ws;

use GatewayWorker\Lib\Gateway;

class Events
{
    // Called when a client connects
    public static function onConnect($client_id)
    {
        // Ask client to auth after connect
        Gateway::sendToClient($client_id, json_encode([
            'type' => 'hello',
            'client_id' => $client_id,
            'next' => 'auth',
        ]));
    }

    // Called when a client sends a message
    public static function onMessage($client_id, $message)
    {
        // Ensure JSON
        $data = json_decode($message, true);
        if (!is_array($data)) {
            Gateway::sendToClient($client_id, json_encode([
                'type' => 'error',
                'error' => 'invalid_json'
            ]));
            return;
        }

        // Route by type
        switch ($data['type'] ?? '') {
            case 'auth':
                // Expect: {type:"auth", token:"...", rooms:["roomA","roomB"]}
                $token  = $data['token'] ?? '';
                $rooms  = $data['rooms'] ?? [];

                // TODO: verify token (JWT/signed) and get $uid
                // For demo we just decode a simple base64 JSON:
                // DO NOT USE IN PRODUCTION — replace with real JWT/signature verify
                $uid = self::verifyToken($token);
                if (!$uid) {
                    Gateway::sendToClient($client_id, json_encode(['type' => 'auth', 'ok' => false]));
                    Gateway::closeClient($client_id);
                    return;
                }

                // Bind user ID to connection
                Gateway::bindUid($client_id, (string)$uid);

                // Join requested rooms
                foreach ($rooms as $room) {
                    Gateway::joinGroup($client_id, (string)$room);
                }

                Gateway::sendToClient($client_id, json_encode([
                    'type' => 'auth',
                    'ok' => true,
                    'uid' => $uid,
                    'rooms' => $rooms
                ]));

                // Notify rooms that user joined
                foreach ($rooms as $room) {
                    Gateway::sendToGroup((string)$room, json_encode([
                        'type' => 'presence.join',
                        'room' => $room,
                        'uid'  => $uid
                    ]));
                }
                break;

            case 'chat.send':
                // {type:"chat.send", room:"roomA", text:"hello"}
                $room = (string)($data['room'] ?? '');
                $text = (string)($data['text'] ?? '');

                if ($room === '' || $text === '') {
                    Gateway::sendToClient($client_id, json_encode([
                        'type' => 'error',
                        'error' => 'missing_room_or_text'
                    ]));
                    return;
                }

                $uid = Gateway::getUidByClientId($client_id);
                $msg = [
                    'type' => 'chat.message',
                    'room' => $room,
                    'from' => $uid ?: null,
                    'text' => $text,
                    'ts'   => time()
                ];

                Gateway::sendToGroup($room, json_encode($msg));
                break;

            case 'room.join':
                // {type:"room.join", room:"roomA"}
                $room = (string)($data['room'] ?? '');
                if ($room) {
                    Gateway::joinGroup($client_id, $room);
                    $uid = Gateway::getUidByClientId($client_id);
                    Gateway::sendToClient($client_id, json_encode(['type'=>'room.joined','room'=>$room]));
                    Gateway::sendToGroup($room, json_encode(['type'=>'presence.join','room'=>$room,'uid'=>$uid]));
                }
                break;

            case 'room.leave':
                // {type:"room.leave", room:"roomA"}
                $room = (string)($data['room'] ?? '');
                if ($room) {
                    Gateway::leaveGroup($client_id, $room);
                    $uid = Gateway::getUidByClientId($client_id);
                    Gateway::sendToClient($client_id, json_encode(['type'=>'room.left','room'=>$room]));
                    Gateway::sendToGroup($room, json_encode(['type'=>'presence.leave','room'=>$room,'uid'=>$uid]));
                }
                break;

            default:
                Gateway::sendToClient($client_id, json_encode([
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
