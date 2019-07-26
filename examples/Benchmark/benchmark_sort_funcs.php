<?php declare(strict_types=1);

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkOptions as Options;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../arguments.php';

// Benchmarking
$options = new Options(); // Example
//$options->
$benchmark = new Benchmark($options);

$benchmark
    ->withComment('Benchmark sort $real')
    ->add(
        'sort',
        $real
    );
$benchmark
    ->add(
        'sort',
        '' // Exception here: TypeError
    );
$benchmark
    ->withComment('Benchmark sort $real2')
    ->add(
        'sort',
        $real2
    );

$report = $benchmark->run();
echo $report->withReturns() . PHP_EOL; // cast BenchmarkReport object to string
