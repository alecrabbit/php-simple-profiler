<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\Tools\BenchmarkSymfonyPB;

const ITERATIONS = 900000;

require_once __DIR__ . '/../../../vendor/autoload.php';

$benchmark = new BenchmarkSymfonyPB(ITERATIONS);

$benchmark->getProgressBar()->setFormat('[%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%');

$benchmark
    ->useName('fast')
    ->withComment('first fast function')
    ->addFunction(
        function ($a) {
            return $a;
        },
        'a'
    );
$benchmark
    ->useName('fast')
    ->withComment('second fast function')
    ->addFunction(
        function ($a) {
            return $a;
        },
        'a'
    );

$counter = 0;
/*
 * Automatically processes exceptions
 * This function will be called only once
*/
$benchmark
    ->withComment('This func throws an exception')
    ->useName('it_throws')
    ->addFunction(
        function () use (&$counter) {
            $counter++;
            throw new \Exception('Simulated exception');
        }
    );

$benchmark
    ->withComment('slow function')
    ->addFunction(
        function ($n) {
            for ($i = 10; $i > 0; $i--) {
                $n++;
            }
            return $n;
        },
        1
    );
$benchmark->run();
$report = $benchmark->getReport();
echo $report . PHP_EOL;
echo 'Called: ' . $counter . PHP_EOL;

// Benchmark:
//1.   4.9μs (  0.00%) ⟨1⟩ λ(string) fast function
//string('a')
//2.  81.7μs (1552.82%) ⟨3⟩ λ(integer) slow function
//integer(101)
//
//Exceptions:
//⟨2⟩ λ() throws Simulated exception
//
//Counter[added]: Value: 3, Step: 1, Bumped: +3 -0, Path: 3, Length: 3, Max: 3, Min: 0, Diff: 3
//Counter[benchmarked]: Value: 2, Step: 1, Bumped: +2 -0, Path: 2, Length: 2, Max: 2, Min: 0, Diff: 2
//Elapsed: 15.1s
