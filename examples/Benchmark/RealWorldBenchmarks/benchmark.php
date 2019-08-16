<?php declare(strict_types=1);
/*
 * Picking best solution for percent
 */

use AlecRabbit\Tools\Benchmark;
use function AlecRabbit\Helpers\bounds;

require_once __DIR__ . '/../../bootstrap.php';

$benchmark = new Benchmark();

$benchmark
    ->withName('concat')
    ->add(
        static function (float $fraction): string {
            return
                '' . number_format($fraction * 100, 0, '.', '') . '%';
        },
        .5
    );
$benchmark
    ->withName('percent')
    ->add(
        [\AlecRabbit\Accessories\Pretty::class, 'percent'],
        .5
    );

$benchmark
    ->withName('sprintf')
    ->add(
        static function (float $fraction): string {
            return
                sprintf(
                    '%s%s%s',
                    '',
                    number_format($fraction * 100, 0, '.', ''),
                    '&'
                );
        },
        .5
    );

$report = $benchmark->run();
echo $report . PHP_EOL; // cast BenchmarkReport object to string

//dump($report); // Optional
