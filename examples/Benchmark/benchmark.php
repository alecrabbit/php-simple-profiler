<?php declare(strict_types=1);

use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkOptions as Options;
use NunoMaduro\Collision\Provider;

require_once __DIR__ . '/../../vendor/autoload.php';

(new Provider)->register(); // Optional line - error handling

// Optional
$themes = new Themes();
echo $themes->comment('Benchmark example') . PHP_EOL;
echo $themes->dark('PHP version: ' . PHP_VERSION) . PHP_EOL;

// Benchmarking
$options = new Options(); // Example
$options->setMethod(Options::DIRECT_MEASUREMENTS);

$benchmark = new Benchmark($options);
$benchmark
    ->withComment('Benchmark hrtime')
    ->add('hrtime', true);

$benchmark
    ->withComment('Benchmark max')
    ->add('max', [2, 3, 4, 5, 5, 3, 3, 3, 5, 56, 7, 3, 23, 3, 5, 6, 7, 76, 3, 3, 6, 7, 7, 3, 3, 2, 2]);

$benchmark
    ->withComment('Benchmark hash md5')
    ->add('hash', 'md5', 'Hello World!');

$benchmark
    ->withComment('Benchmark hash sha1')
    ->add('hash', 'sha1', 'Hello World!');

$report = $benchmark->run();
echo $report->withReturns() . PHP_EOL; // cast BenchmarkReport object to string

dump($report); // Optional
