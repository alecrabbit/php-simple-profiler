<?php declare(strict_types=1);

/*
 * Picking best solution for percent
 */

use AlecRabbit\Tools\Benchmark;

require_once __DIR__ . '/../../bootstrap.php';

$benchmark = new Benchmark();

$benchmark
    ->withName('number_format')
    ->add(
        static function (float $fraction): string {
            return
                number_format($fraction * 100);
        },
        .5123
    );
$benchmark
    ->withName('round')
    ->add(
        static function (float $fraction): string {
            return
                (string)round($fraction * 100);
        },
        .5123
    );
$benchmark
    ->withName('ceil')
    ->add(
        static function (float $fraction): string {
            return
                (string)ceil($fraction * 100);
        },
        .5123
    );
$benchmark
    ->withName('casting')
    ->add(
        static function (float $fraction): string {
            return
                (string)(int)($fraction * 100);
        },
        .5123
    );

$report = $benchmark->run();
echo $report . PHP_EOL; // cast BenchmarkReport object to string

dump($report); // Optional
