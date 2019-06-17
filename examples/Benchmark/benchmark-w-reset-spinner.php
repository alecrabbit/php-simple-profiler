<?php

use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Control\Cursor;
use AlecRabbit\Spinner\SnakeSpinner;
use AlecRabbit\Tools\BenchmarkWithSpinner;
use function AlecRabbit\typeOf;

const ITERATIONS = 1400000;

require_once __DIR__ . '/../../vendor/autoload.php';

$theme = new Themes();

//$spinner = new SimpleSpinner('benchmarking');
//$spinner = new ClockSpinner('benchmarking');
//$spinner = new MoonSpinner('benchmarking');
$spinner = new SnakeSpinner('benchmarking');

if (extension_loaded('xdebug')) {
    echo $theme->warning('XDebug extension is loaded, benchmarking will be slow!') . PHP_EOL;
}
echo $theme->comment('Benchmark with [' . typeOf($spinner) . '] progress indicator') . PHP_EOL;
echo $theme->dark('PHP version: ' . PHP_VERSION) . PHP_EOL;

try {
    $benchmark = new BenchmarkWithSpinner(ITERATIONS, false, $spinner);
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
    echo $report = $benchmark->report();
    echo $benchmark->stat();
    dump($report);
} catch (Exception $e) {
    echo 'Error occurred: ';
    echo '[' . $theme->error(typeOf($e)) . '] ' . $e->getMessage() . PHP_EOL;
    echo Cursor::show();
}
