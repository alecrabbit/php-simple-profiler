<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\Tools\Benchmark;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Automatically processes exceptions
*/

$benchmark = new Benchmark(50000);
$benchmark
    ->withComment('fast function')
    ->addFunction(
        function ($a) {
            return $a;
        },
        'a'
    );
$benchmark
    ->withComment('slow function')
    ->addFunction(
        function ($n) {
            for ($i = 1500; $i > 0; $i--) {
                $n++;
            }
            return $n;
        },
        1
    );
$benchmark
    ->withComment('throws')
    ->addFunction(
        function () {
            throw new \Exception('Simulated exception');
        }
    );
$benchmark
    ->returnResults()
    ->verbose()
    ->color()
    ->run(true);
echo $benchmark->elapsed() . PHP_EOL;
