<?php

use AlecRabbit\Tools\Benchmark;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Let's determine what is faster hrtime or microtime
*/

$benchmark = new Benchmark(900000);


$benchmark
    ->addFunction('hrtime', true); // slightly faster on php^7.3

$benchmark
    ->addFunction('microtime', true); // significantly faster on php7.2

$benchmark
    ->returnResults()
    ->verbose()
    ->color()
    ->run(true);
echo $benchmark->elapsed() . PHP_EOL;
//dump($benchmark);
