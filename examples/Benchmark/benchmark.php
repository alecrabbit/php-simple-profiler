<?php declare(strict_types=1);

use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkOptions as Options;
use NunoMaduro\Collision\Provider;

require_once __DIR__ . '/../bootstrap.php';

// Benchmarking
$options = new Options(); // Example

$benchmark = new Benchmark($options);
$benchmark
    ->withComment('Some comment regarding hrtime')
    ->add('hrtime', true);

$benchmark
    ->withComment('Another comment for max')
    ->add('max', [2, 3, 4, 5, 5, 3, 3, 3, 5, 56, 7, 3, 23, 3, 5, 6, 7, 76, 3, 3, 6, 7, 7, 3, 3, 2, 2]);

$benchmark
    ->withName('hash_md5')
    ->add('hash', 'md5', 'Hello World!');

$benchmark
    ->withName('hash_sha1')
    ->add('hash', 'sha1', 'Hello World!');

$report = $benchmark->run();
echo $report . PHP_EOL; // cast BenchmarkReport object to string

//dump($report); // Optional
