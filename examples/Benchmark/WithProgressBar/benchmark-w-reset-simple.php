<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkSimplePB;
use AlecRabbit\Tools\BenchmarkSymfonyPB;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

const ITERATIONS = 900000;

require_once __DIR__ . '/../../../vendor/autoload.php';



$benchmark = new BenchmarkSimplePB(ITERATIONS);


$benchmark
    ->withComment('floatval()')
    ->addFunction('floatval', '3.5');

$benchmark
    ->withComment('intval()')
    ->addFunction('intval', '3');

$benchmark->run();
$report = $benchmark->getReport();
echo $report . PHP_EOL;

$benchmark->reset();
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


$benchmark->run();
$report = $benchmark->getReport();
echo $report . PHP_EOL;

$benchmark->reset();
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

$benchmark->run();
$report = $benchmark->getReport();
echo $report . PHP_EOL;
echo $benchmark->elapsed() . PHP_EOL;
