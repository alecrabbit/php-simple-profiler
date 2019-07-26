<?php declare(strict_types=1);

use AlecRabbit\Tools\Benchmark;

if (!extension_loaded('trader')) {
    echo 'This example requires trader extension.' . PHP_EOL;
    exit(1);
}

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../arguments.php';

$benchmark = new Benchmark();

$benchmark
    ->withComment('Benchmark trader_ema $real2')
    ->add('trader_ema', $real2);
$benchmark
    ->withComment('Benchmark trader_ema $real')
    ->add('trader_ema', $real);

$report = $benchmark->run();
echo $report->withReturns() . PHP_EOL; // cast BenchmarkReport object to string

//dump($report); // Optional
