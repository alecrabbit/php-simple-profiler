<?php

use AlecRabbit\Tools\Benchmark;

require_once __DIR__ . '/../../vendor/autoload.php';

echo 'Benchmark with no progress output' . PHP_EOL;
echo 'PHP version: ' . PHP_VERSION . PHP_EOL;

/*
 * Let's determine what is faster hrtime or microtime
*/
$benchmark = new Benchmark(900000);


$benchmark
    ->addFunction('hrtime', true); // slightly faster on php^7.3 (hrtime native for ^7.3)

$benchmark
    ->addFunction('microtime', true); // significantly faster on php7.2 (hrtime is a polyfill func for under 7.3)

echo $benchmark->run()->getReport() . PHP_EOL;
echo $benchmark->elapsed() . PHP_EOL;
//dump($benchmark);
