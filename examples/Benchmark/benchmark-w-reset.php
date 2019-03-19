<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\Tools\Factory;

require_once __DIR__ . '/../../vendor/autoload.php';

try {
    Factory::setDefaultIterations(1000); // optional
    $benchmark = Factory::createBenchmark();
    $benchmark
        ->withComment('floatval()')
        ->addFunction('floatval', '3.5');
    $benchmark
        ->withComment('(float)')
        ->addFunction(function () {
            return (float)'3.5';
        });
    $benchmark
        ->withComment('float "+"')
        ->addFunction(function () {
            return +'3.5';
        });
    $report = $benchmark
        ->withComment('Comparing...')
        ->showReturns()
        ->report();
    echo $report;
//    dump($report);

    $benchmark->reset();

    $benchmark
        ->withComment('(int)')
        ->addFunction(function () {
            return (int)'3';
        });

    $benchmark
        ->withComment('int "+"')
        ->addFunction(function () {
            return +'3';
        });

    $report = $benchmark
        ->withComment('Second section...')
        ->showReturns()
        ->report();
    echo $report;
//    dump($report);
    echo $benchmark->stat() . PHP_EOL;
} catch (Exception $e) {
    echo 'Error occurred: ';
    echo $e->getMessage() . PHP_EOL;
}
