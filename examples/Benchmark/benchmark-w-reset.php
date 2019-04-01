<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\Tools\Factory;

require_once __DIR__ . '/../../vendor/autoload.php';

try {
    Factory::setDefaultIterations(500000); // optional
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
        ->withComment('Comparing...' . PHP_EOL . 'First section... (Constants)')
        ->showReturns()
        ->report();
    echo $report;

    $benchmark->reset();

    $arg = '3.5';
    $benchmark
        ->withComment('floatval()')
        ->addFunction('floatval', $arg);

    $benchmark
        ->withComment('(float)')
        ->addFunction(function ($a) {
            return (float)$a;
        }, $arg);

    $benchmark
        ->withComment('float "+"')
        ->addFunction(function ($a) {
            return +$a;
        }, $arg);

    $report = $benchmark
        ->withComment('Second section... (Arguments)')
        ->showReturns()
        ->report();
    echo $report;

    $benchmark->reset();

    $benchmark
        ->withComment('intval()')
        ->addFunction('intval', '3');

    $benchmark
        ->withComment('(int)')
        ->addFunction(
            function () {
                return (int)'3';
            }
        );

    $benchmark
        ->withComment('int "+"')
        ->addFunction(
            function () {
                return +'3';
            }
        );

    $report = $benchmark
        ->withComment('Third section... (Constants)')
        ->showReturns()
        ->report();
    echo $report;

    $benchmark->reset();

    $arg = '3';

    $benchmark
        ->withComment('intval()')
        ->addFunction('intval', $arg);

    $benchmark
        ->withComment('(int)')
        ->addFunction(
            function ($a) {
                return (int)$a;
            },
            $arg
        );

    $benchmark
        ->withComment('int "+"')
        ->addFunction(
            function ($a) {
                return +$a;
            },
            $arg
        );

    $report = $benchmark
        ->withComment('Fourth section... (Arguments)')
        ->showReturns()
        ->report();
    echo $report;

    $benchmark->reset();

    $benchmark
        ->useName('one')
        ->addFunction(
            function () {
                return 1;
            }
        );

    $benchmark
        ->useName('two')
        ->addFunction(
            function () {
                return 1;
            }
        );
    $benchmark
        ->useName('three')
        ->addFunction(
            function () {
                throw new \RuntimeException('Simulated exception');
            },
            'argument'
        );

    $benchmark
        ->withComment('Default name for closure')
        ->addFunction(
            function () {
                throw new \BadFunctionCallException('Simulated exception');
            }
        );

    $report = $benchmark
        ->withComment('Fifth section... (Exceptions)')
        ->showReturns()
        ->report();
    echo $report;

    echo $benchmark->stat() . PHP_EOL;
} catch (Exception $e) {
    echo 'Error occurred: ';
    echo $e->getMessage() . PHP_EOL;
}
