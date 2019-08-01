<?php declare(strict_types=1);

use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Internal\BenchmarkOptions as Options;
use NunoMaduro\Collision\Provider;

require_once __DIR__ . '/../../vendor/autoload.php';

(new Provider)->register(); // Optional line - error handling

// Optional
$themes = new Themes();
echo $themes->comment('Benchmark example') . PHP_EOL;
echo $themes->dark('PHP version: ' . PHP_VERSION) . PHP_EOL;

// Benchmarking
$options = new Options(); // Example
//$options->setMethod(Options::DIRECT_MEASUREMENTS);

$benchmark = new Benchmark($options);
$benchmark
    ->withComment('floatval(intval($value))')
    ->withName('functions')
    ->add(
        static function ($value) {
            if (is_float($value) && floatval(intval($value)) === $value) {
                return "$value.0";
            }
        },
        1.0
    );
$benchmark
    ->withComment('(float)(int)$value')
    ->withName('casting')
    ->add(
        static function ($value) {
            if (is_float($value) && (float)(int)$value === $value) {
                return "$value.0";
            }
        },
        1.0
    );

$report = $benchmark->run();
echo $report . PHP_EOL; // cast BenchmarkReport object to string

//dump($report); // Optional
