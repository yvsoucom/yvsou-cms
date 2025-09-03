<?php
use Workerman\Worker;

require __DIR__ . '/vendor/autoload.php';

$register = new Worker('text://0.0.0.0:1238');
Worker::runAll();
