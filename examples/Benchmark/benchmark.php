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
        function ($a) {
            return sprintf(
                '%s - %s%s%s%s%s%s%s%s',
                $a,
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8'
            );
        },
        '222'
    );
$benchmark
    ->withComment('Some comment for exception')
    ->withName('except')
    ->add(
        function ($a) {
            return $a . ' - ' . '1' . '2' . '3' . '4' . '5' . '6' . '7' . '8';
        },
        '222'
    );
$report = $benchmark->run();
echo $report->withReturns() . PHP_EOL;
dump($report);
