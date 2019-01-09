<?php

use AlecRabbit\Tools\Benchmark;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Let's determine what is faster array_key_first or array_key_first
*/

$benchmark = new Benchmark(900000);

function array_key_first_old(array $data)
{
    reset($data);
    return key($data);
}


$a = [1,2,3,4,5,6,76,7,78,4,2,2,3,4,56,6,5,3,2,];

$benchmark
    ->addFunction('array_key_first', $a);

$benchmark
    ->addFunction('array_key_first_old', $a);

$benchmark
    ->returnResults()
    ->verbose()
    ->color()
    ->run(true);
echo $benchmark->elapsed() . PHP_EOL;
//dump($benchmark);
