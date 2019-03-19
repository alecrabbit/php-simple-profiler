<?php

use AlecRabbit\Tools\Benchmark;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

const ITERATIONS = 5000;

require_once __DIR__ . '/../../../vendor/autoload.php';

$output = new ConsoleOutput();

$progressBar = new ProgressBar($output, 100);
$progressBar->setBarWidth(80);

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
$benchmark->showProgressBy($progressStart, $progressAdvance, $progressFinish);
$benchmark->run();
$report = $benchmark->report();
echo $report . PHP_EOL;
echo $benchmark->stat() . PHP_EOL;