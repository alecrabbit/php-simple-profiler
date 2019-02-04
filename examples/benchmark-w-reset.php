<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\Tools\Benchmark;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

const ITERATIONS = 90000;

require_once __DIR__ . '/../vendor/autoload.php';

$output = new ConsoleOutput();
$progressBar = new ProgressBar($output, 100);
$progressBar->setBarWidth(80);
$progressBar->setFormat('[%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%');

$benchmark = new Benchmark(ITERATIONS);

$progressStart = function () use ($progressBar) {
    $progressBar->start();
};

$progressAdvance = function () use ($progressBar) {
    $progressBar->advance();
};

$progressFinish = function () use ($progressBar) {
    $progressBar->finish();
    $progressBar->clear();
};

$benchmark->progressBar($progressStart, $progressAdvance, $progressFinish);

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
