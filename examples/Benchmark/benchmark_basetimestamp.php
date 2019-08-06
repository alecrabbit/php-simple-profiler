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
$options->setMaxIterations(18);
$time = time();
$benchmark = new Benchmark($options);
$benchmark
    ->add(
        static function (int $timestamp, int $interval = 60) {
            return \intdiv($timestamp, $interval) * $interval;
        },
        $time
    );
$benchmark
    ->add(
        static function (int $timestamp, int $interval = 60) {
            return ((int)($timestamp / $interval)) * $interval;
        },
        $time
    );
$benchmark
    ->add(
        'base_timestamp_new',
        $time
    );
$benchmark
    ->add(
        'base_timestamp',
        $time
    );

$report = $benchmark->run();
echo $report . PHP_EOL; // cast BenchmarkReport object to string

//dump($report); // Optional


/*   Functions    */

function base_timestamp_new(int $timestamp, int $interval = 60)
{
    return ((int)($timestamp / $interval)) * $interval;
}

function base_timestamp(int $timestamp, int $interval = 60)
{
    return \intdiv($timestamp, $interval) * $interval;
}