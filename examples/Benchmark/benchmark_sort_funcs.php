<?php declare(strict_types=1);

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Internal\BenchmarkOptions as Options;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../arguments.php';

// Benchmark
$options = new Options(); // Example
$options->setMaxIterations(5);

$output = null;
// Example using output object
//$output = new Symfony\Component\Console\Output\ConsoleOutput(
//    Symfony\Component\Console\Output\ConsoleOutput::VERBOSITY_QUIET // No output during benchmarking
//);

$benchmark = new Benchmark($options, $output);

$benchmark
    ->withComment('Benchmark sort $real')
    ->add('sort', $real);
$benchmark
    ->add('sort', ''); // Exception here: TypeError
$benchmark
    ->withComment('Benchmark sort $real2')
    ->add('sort', $real2);

// Run benchmark and get report
$report = $benchmark->run();
echo $report . PHP_EOL; // cast BenchmarkReport object to string
