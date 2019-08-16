<?php declare(strict_types=1);

/*
 * Picking best solution for percent
 */

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Internal\BenchmarkOptions;

require_once __DIR__ . '/../../bootstrap.php';

$options = new BenchmarkOptions();
$options->setMaxIterations(9);
$benchmark = new Benchmark($options);
$prefix = ' ';
$benchmark
    ->withComment('Double calc')
    ->add(
        static function (?float $fraction) use ($prefix): string {
            if ((null !== $fraction) && 0 === (int)($fraction * 1000) % 10) {
                return $prefix . (int)($fraction * 100) . '%';
            }
        },
        .51
    );
$benchmark
    ->withComment('Additional var')
    ->add(
        static function (?float $fraction) use ($prefix): string {
            if ((null !== $fraction) && 0 === ($fractionVal = (int)($fraction * 1000)) % 10) {
                return $prefix . ($fractionVal / 10) . '%';
            }
        },
        .51
    );

$report = $benchmark->run();
echo $report . PHP_EOL; // cast BenchmarkReport object to string

//dump($report); // Optional
