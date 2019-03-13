<?php declare(strict_types=1);

use AlecRabbit\Tools\Benchmark;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;


const ITERATIONS = 5000000;

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
    ->withComment('floatval(intval())')
    ->addFunction(
        function ($value) {
            if (is_float($value) && floatval(intval($value)) === $value) {
                return "$value.0";
            }
        },
        1.0
    );

$benchmark
    ->withComment('(float)(int)')
    ->addFunction(
        function ($value) {
            if (is_float($value) && (float)(int)$value === $value) {
                return "$value.0";
            }
        },
        1.0
    );



$benchmark->showProgressBy($progressStart, $progressAdvance, $progressFinish);
$benchmark->run()->showReturns();
$report = $benchmark->report();
echo $report . PHP_EOL;
echo $benchmark->stat() . PHP_EOL;
