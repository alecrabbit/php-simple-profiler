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

$func =
    static function (array $a) {
        return array_sum($a);
    };

$benchmark
    ->withComment('Call signature: call_user_func($func, ...$args)')
    ->useName('call_user_func')
    ->addFunction('\call_user_func', $func, $a);

$benchmark
    ->withComment('Call signature: $func(...$args)')
    ->useName('$func')
    ->addFunction($func, $a);

echo $benchmark->report() . PHP_EOL;
echo $benchmark->stat() . PHP_EOL;
