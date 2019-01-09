<?php

use AlecRabbit\Tools\Benchmark;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Let's determine what is faster \call_user_func($func, ...$args) or $func(...$args)
*/

$benchmark = new Benchmark(500000);
$args = [1, 2, 3];

$func = function ($a, $b, $c) {
    return $a + $b + $c;
};

$benchmark
    ->withComment('\call_user_func($func, ...$args)')
    ->useName('call_user_func')
    ->addFunction(
        function ($args) use ($func) {
            return \call_user_func($func, ...$args);
        },
        $args
    );

$benchmark
    ->withComment('$func(...$args)')
    ->useName('$func')
    ->addFunction(
        function ($args) use ($func) {
            return $func(...$args);
        },
        $args
    );

$benchmark
    ->returnResults()
    ->verbose()
    ->color()
    ->run(true);
echo $benchmark->elapsed() . PHP_EOL;
dump($benchmark);