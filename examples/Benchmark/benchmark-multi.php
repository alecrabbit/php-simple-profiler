<?php

use AlecRabbit\Tools\Factory;
use function AlecRabbit\brackets;
use function AlecRabbit\typeOf;

require_once __DIR__ . '/../../vendor/autoload.php';

try {
    Factory::setDefaultIterations(500000); // optional
    $benchmark1 = Factory::createBenchmark();

    $benchmark1
        ->withComment('(float)')
        ->addFunction(function () {
            return (float)'3.5';
        });

    $benchmark1
        ->withComment('float "+"')
        ->addFunction(function () {
            return +'3.5';
        });

    $benchmark1
        ->withComment('float "+"')
        ->addFunction(function () {
            throw new \RuntimeException('Simulated exception');
        });

    $report = $benchmark1
        ->withComment('Comparing...' . PHP_EOL . 'Benchmark 1 First section... ')
        ->showReturns()
        ->report();
    echo $report;
    echo $benchmark1->stat() . PHP_EOL;
    echo PHP_EOL;

    echo 'Throws an exception here:' . PHP_EOL;
    try {
        $benchmark1
            ->withComment('floatval()')
            ->addFunction('floatval', '3.5');
    } catch (\RuntimeException $e) {
        echo brackets(typeOf($e)) . ' ' . $e->getMessage() . PHP_EOL;
    }
    echo PHP_EOL;
    echo $benchmark1->reset('═');

    $benchmark2 = Factory::createBenchmark();

    $benchmark2
        ->withComment('floatval()')
        ->addFunction('floatval', '3.5');

    $benchmark2
        ->withComment('(float)')
        ->addFunction(function () {
            return (float)'3.5';
        });

    $benchmark2
        ->withComment('float "+"')
        ->addFunction(function () {
            return +'3.5';
        });

    $report = $benchmark2
        ->withComment('Comparing...' . PHP_EOL . 'Benchmark 2 First section... ')
        ->showReturns()
        ->report();
    echo $report;

    echo $benchmark2->reset();

    $benchmark2
        ->withComment('Fast function')
        ->addFunction(
            function () {
                return (int)'3';
            }
        );

    $benchmark2
        ->withComment('Function throws an exception')
        ->addFunction(
            function () {
                throw new \RuntimeException('Simulated exception');
            }
        );

    $benchmark2
        ->withComment('Slow function')
        ->addFunction(
            function ($a) {
                for ($i = 0; $i < 10; $i++) {
                    $a += $i; // dummy
                }
                return 3;
            },
            2
        );

    $report = $benchmark2
        ->withComment('Benchmark 2 Second section...')
        ->showReturns()
        ->report();

    echo $report;

    echo $benchmark2->reset();

    $benchmark2
        ->withComment('Fast function')
        ->addFunction(
            function ($a) {
                for ($i = 0; $i < 4; $i++) {
                    $a++; // dummy
                }
                return 10;
            },
            2
        );
    $benchmark2
        ->withComment('Middle speed function')
        ->addFunction(
            function ($a) {
                for ($i = 0; $i < 14; $i++) {
                    $a++; // dummy
                }
                return 10;
            },
            2
        );
    $benchmark2
        ->withComment('Slow function')
        ->addFunction(
            function ($a) {
                for ($i = 0; $i < 40; $i++) {
                    $a++; // dummy
                }
                return 10;
            },
            2
        );

    $report = $benchmark2
        ->withComment('Benchmark 2 Third section...')
        ->showReturns()
        ->report();

    echo $report;

    echo $benchmark2->stat() . PHP_EOL;
} catch (Exception $e) {
    echo 'Error occurred: ';
    echo $e->getMessage() . PHP_EOL;
}
