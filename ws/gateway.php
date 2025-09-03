<?php
use Workerman\Worker;
use GatewayWorker\Gateway;

require __DIR__ . '/vendor/autoload.php';

$gateway = new Gateway('websocket://0.0.0.0:2346');
$gateway->name = 'WebSocketGateway';
$gateway->count = 2;                          // processes
$gateway->registerAddress = '127.0.0.1:1238';
$gateway->pingInterval = 25;
$gateway->pingNotResponseLimit = 2;
$gateway->pingData = json_encode(['type' => 'ping']);

Worker::runAll();
