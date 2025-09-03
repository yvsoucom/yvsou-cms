<?php
use Workerman\Worker;
use GatewayWorker\BusinessWorker;

require __DIR__ . '/vendor/autoload.php';

$worker = new BusinessWorker();
$worker->name = 'BusinessWorker';
$worker->count = 2;
$worker->registerAddress = '127.0.0.1:1238';

// Set entry class for events (next section)
$worker->eventHandler = \App\Ws\Events::class;

Worker::runAll();
