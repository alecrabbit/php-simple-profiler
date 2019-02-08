<?php

use AlecRabbit\Tools\Benchmark;

require_once __DIR__ . '/../../vendor/autoload.php';

/*
 * Let's determine what is faster array_key_first or array_key_first
*/

$benchmark = new Benchmark(900);

function another_implementation(array $data)
{
    reset($data);
    return key($data);
}


$a = ['a' => 1, 2, 3, 4, 5, ];

$benchmark
    ->addFunction('array_key_first', $a);

$benchmark
    ->addFunction('another_implementation', $a);

echo $benchmark->run()->getReport() . PHP_EOL;
echo $benchmark->elapsed() . PHP_EOL;
//dump($benchmark);
