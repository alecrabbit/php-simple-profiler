<?php declare(strict_types=1);

use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkOptions;
use NunoMaduro\Collision\Provider;

require_once __DIR__ . '/../../vendor/autoload.php';

(new Provider)->register(); // Optional line - error handling

$themes = new Themes();
echo $themes->comment('Benchmark example') . PHP_EOL;
echo $themes->dark('PHP version: ' . PHP_VERSION) . PHP_EOL;

$options = new BenchmarkOptions();

$benchmark = new Benchmark($options);
$benchmark
    ->withComment('Some comment')
    ->withName('addition')
    ->add(
        function ($value) {
            $a = 0;
            for ($i = 0; $i < 1000; $i++) {
                $a += $i * 2;
            }
            return $a + $value;
        },
        1.0
    );
$benchmark
    ->withComment('Some comment for exception')
    ->withName('except')
    ->add(
        function ($value) {
            if (is_float($value) && ((float)(int)$value) === $value) {
                return "$value.0";
            }
        },
        1.0
    );
$report = $benchmark->run();
echo $report->withReturns() . PHP_EOL;
dump($report);
