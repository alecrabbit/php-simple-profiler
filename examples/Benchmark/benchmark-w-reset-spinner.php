<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Control\Cursor;
use AlecRabbit\Spinner\ClockSpinner;
use AlecRabbit\Tools\BenchmarkWithSpinner;
use function AlecRabbit\typeOf;

const ITERATIONS = 900000;

require_once __DIR__ . '/../../vendor/autoload.php';

$theme = new Themes();

$spinner = new ClockSpinner('benchmarking');
echo $theme->comment('Benchmark with [' . typeOf($spinner) . '] progress indicator') . PHP_EOL;
echo $theme->dark('PHP version: ' . PHP_VERSION) . PHP_EOL;

$benchmark = new BenchmarkWithSpinner(ITERATIONS, false, $spinner);

echo Cursor::hide();
try {
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

    echo $benchmark->report();
    echo $benchmark->reset();
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

    $benchmark
        ->withComment('intval()')
        ->addFunction('intval', '3');
    echo $benchmark->report();
    echo $benchmark->stat();
} catch (Exception $e) {
    echo 'Error occurred: ';
    echo '[' . $theme->error(typeOf($e)) . '] ' . $e->getMessage() . PHP_EOL;
}
echo Cursor::show();
