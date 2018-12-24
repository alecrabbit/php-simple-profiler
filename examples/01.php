<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\Tools\Benchmark;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * There are moments when you have to choose between two or more different approaches
 * Benchmark class is to help you choose which is faster :)
*/

$benchmark = new Benchmark(50000);

$benchmark
    ->withComment('floatval()')
    ->addFunction('floatval', '3.5');

$benchmark
    ->withComment('intval()')
    ->addFunction('intval', '3');


$benchmark
    ->withComment('(float)')
    ->addFunction(function () {
        return (float)'3.5';
    });

$benchmark
    ->withComment('float "+"')
    ->addFunction(function () {
        return +'3.5';
    });

$benchmark
    ->withComment('(int)')
    ->addFunction(function () {
        return (int)'3';
    });

$benchmark
    ->withComment('int "+"')
    ->addFunction(function () {
        return +'3';
    });

$benchmark
    ->returnResults()
    ->verbose()
    ->color()
    ->run(true);
echo $benchmark->elapsed() . PHP_EOL;
