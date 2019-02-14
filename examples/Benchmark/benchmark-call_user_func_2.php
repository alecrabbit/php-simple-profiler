<?php

use AlecRabbit\Tools\Benchmark;

require_once __DIR__ . '/../../vendor/autoload.php';

echo 'Benchmark with no progress output' . PHP_EOL;
echo 'PHP version: ' . PHP_VERSION . PHP_EOL;

/*
 * Let's determine what is faster \call_user_func($func, ...$args) or $func(...$args)
*/

$benchmark = new Benchmark(500000);
$a = [1, 2, 3];

$func = function (array $a) {
    return array_sum($a);
};

$benchmark
    ->withComment('call_user_func($func, ...$args)')
    ->addFunction('\call_user_func', $func, $a);

$benchmark
    ->withComment('$func(...$args)')
    ->useName('$func')
    ->addFunction($func, $a);

$benchmark->run();
echo $benchmark->getReport() . PHP_EOL;
echo $benchmark->elapsed() . PHP_EOL;
