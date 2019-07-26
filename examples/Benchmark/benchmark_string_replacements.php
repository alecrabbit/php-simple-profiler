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
    (new BenchmarkOptions())->setMaxIterations(7)
);
$benchmark
    ->add('sprintf', '>>>%s<<<', '222');
$benchmark
    ->add('str_replace', '%s', '222', '>>>%s<<<');
//$benchmark
//    ->withComment('sprintf')
//    ->add(
//        function ($a) {
//            return sprintf('>>>%s<<<', $a);
//        },
//        '222'
//    );
//$benchmark
//    ->withComment('sprintf static')
//    ->add(
//        static function ($a) {
//            return sprintf('>>>%s<<<', $a);
//        },
//        '222'
//    );
//$benchmark
//    ->withComment('str_replace')
//    ->add(
//        function ($a) {
//            return str_replace('%s', $a, '>>>%s<<<');
//        },
//        '222'
//    );

$report = $benchmark->run();
echo $report->withReturns() . PHP_EOL; // cast BenchmarkReport object to string

//dump($report); // Optional
