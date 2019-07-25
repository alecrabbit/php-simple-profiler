<?php declare(strict_types=1);

use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkOptions as Options;
use NunoMaduro\Collision\Provider;

require_once __DIR__ . '/../../vendor/autoload.php';

(new Provider)->register(); // Optional line - error handling

if (!extension_loaded('trader')) {
    echo 'This example requires trader extension.' . PHP_EOL;
    exit(1);
}

// Optional
$themes = new Themes();
echo $themes->comment('Benchmark example') . PHP_EOL;
echo $themes->dark('PHP version: ' . PHP_VERSION) . PHP_EOL;


// Benchmarking
$options = new Options(); // Example
//$options->setMethod(Options::DIRECT_MEASUREMENTS);

$benchmark = new Benchmark($options);
$real = [
    1, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4,
    3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3,
    1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3,
    5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2,
    2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5,
    1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3,
    3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1,
    1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5,
    5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2,
    5, 6, 3, 2, 2, 3, 4, 5, 5, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
];
echo 'Size of $real:' . count($real) . PHP_EOL;
$benchmark
    ->withComment('Benchmark hrtime')
    ->add(
        'trader_ema',
        $real
    );

$report = $benchmark->run();
echo $report->withReturns() . PHP_EOL; // cast BenchmarkReport object to string

//dump($report); // Optional
