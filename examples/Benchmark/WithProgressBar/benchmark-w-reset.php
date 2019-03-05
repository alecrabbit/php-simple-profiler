<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\Tools\OldBenchmark;
use AlecRabbit\Tools\OldBenchmarkSymfonyPB;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

const ITERATIONS = 900000;

require_once __DIR__ . '/../../../vendor/autoload.php';

$benchmark = new OldBenchmarkSymfonyPB(ITERATIONS);
$progressBar = $benchmark->getProgressBar();
$progressBar->setBarWidth(60);
$progressBar->setFormat('[%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%');


$benchmark
    ->withComment('floatval()')
    ->addFunction('floatval', '3.5');
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

$benchmark
    ->withComment('intval()')
    ->addFunction('intval', '3');
$benchmark->run();
$report = $benchmark->getReport();
echo $report . PHP_EOL;

echo $benchmark->stat() . PHP_EOL;
