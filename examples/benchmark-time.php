<?php

use AlecRabbit\Tools\Benchmark;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Let's determine what is faster array_key_first or array_key_first
*/

$benchmark = new Benchmark(900000);


$benchmark
    ->addFunction('hrtime', true);

$benchmark
    ->addFunction('microtime', true);

$benchmark
    ->returnResults()
    ->verbose()
    ->color()
    ->run(true);
echo $benchmark->elapsed() . PHP_EOL;
//dump($benchmark);
