<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\Tools\Benchmark;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

const ITERATIONS = 90000;

require_once __DIR__ . '/../vendor/autoload.php';

$output = new ConsoleOutput();
$progressBar = new ProgressBar($output, 100);
$progressBar->setBarWidth(80);
$progressBar->setFormat('[%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%');

$benchmark = new Benchmark(ITERATIONS);

$progressStart = function () use ($progressBar) {
    $progressBar->start();
};

$progressAdvance = function () use ($progressBar) {
    $progressBar->advance();
};

$progressFinish = function () use ($progressBar) {
    $progressBar->finish();
    $progressBar->clear();
};

$benchmark->progressBar($progressStart, $progressAdvance, $progressFinish);

/*
 * Automatically processes exceptions
*/
$benchmark
    ->withComment('fast function')
    ->addFunction(
        function ($a) {
            return $a;
        },
        'a'
    );

$benchmark
    ->withComment('throws')
    ->addFunction(
        function () {
            throw new \Exception('Simulated exception');
        }
    );

$benchmark
    ->withComment('slow function')
    ->addFunction(
        function ($n) {
            for ($i = 100; $i > 0; $i--) {
                $n++;
            }
            return $n;
        },
        1
    );
$benchmark->run();
$report = $benchmark->getReport();
echo $report . PHP_EOL;
echo $benchmark->elapsed() . PHP_EOL;

// approx. output:
//
//Running benchmarks(50000):
//....................
//
//Benchmark:
//327.7ns (+0.0%) ⟨0⟩ Closure::__invoke(string) "fast function"
//return: string "'a'"
//18.2μs (+5,462.1%) ⟨1⟩ Closure::__invoke(integer) "slow function"
//return: integer "1501"
//Exceptions:
//[⟨2⟩ Closure::__invoke]: [Exception]: Simulated exception
//
//Counter: 2(1)
//Elapsed: 1s
//Timer:[⟨0⟩ Closure::__invoke] Average: 327.7ns, Last: 0ns, Min(3): 0ns, Max(38577): 31μs, Count: 50000
//Timer:[⟨1⟩ Closure::__invoke] Average: 18.2μs, Last: 18.1μs, Min(2): 16.9μs, Max(35010): 88.9μs, Count: 50000
//Timer:[⟨2⟩ Closure::__invoke] Exception encountered
//
//Done in: 1031.053ms
