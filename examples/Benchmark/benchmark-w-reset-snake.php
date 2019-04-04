<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Tools\BenchmarkSimpleProgressBar;
use AlecRabbit\Tools\BenchmarkSnakeProgressIndicator;
use function AlecRabbit\typeOf;

const ITERATIONS = 900000;

require_once __DIR__ . '/../../vendor/autoload.php';

$theme = new Themes();
echo $theme->comment('Benchmark with simple progress bar') . PHP_EOL;
echo $theme->dark('PHP version: ' . PHP_VERSION) . PHP_EOL;

$benchmark = new BenchmarkSnakeProgressIndicator(ITERATIONS);

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
    echo  '['. $theme->error(typeOf($e)) . '] ' . $e->getMessage() . PHP_EOL;
}
