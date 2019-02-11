<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\Tools\BenchmarkSymfonyPB;

const ITERATIONS = 5000;

require_once __DIR__ . '/../../../vendor/autoload.php';

$benchmark = new BenchmarkSymfonyPB(ITERATIONS);


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
$report = $benchmark->run()->getReport();
echo $report . PHP_EOL;
echo $benchmark->elapsed() . PHP_EOL;
//dump($report);
