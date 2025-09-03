<?php
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

    public function sendToUid(string $uid, array $payload): void
    {
        Gateway::sendToUid($uid, json_encode($payload));
    }

    public function sendToRoom(string $room, array $payload): void
    {
        Gateway::sendToGroup($room, json_encode($payload));
    }
}
