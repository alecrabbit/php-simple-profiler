<?php declare(strict_types=1);

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkOptions;

require_once __DIR__ . '/../bootstrap.php';

const EMPTY_ELEMENTS = ['', null, false];

$args = [
    [1, 2, 3, 4, 5, 6, 7, 8, 9, null, 0],
    3,
    static function (&$item, $key) {
        $item = '[' . $key . '] ' . $item;
    },
];

$benchmark = new Benchmark(
//    (new BenchmarkOptions())->setMaxIterations(9)
);
$benchmark
    ->add('sprintf', '>>>%s<<<', '222');
$benchmark
    ->add('str_replace', '%s', '222', '>>>%s<<<');

$report = $benchmark->run();
echo $report->withReturns() . PHP_EOL; // cast BenchmarkReport object to string

//dump($report); // Optional
