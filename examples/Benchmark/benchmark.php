<?php declare(strict_types=1);

use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkOptions;
use NunoMaduro\Collision\Provider;
use Webmozart\Assert\Assert;

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
        static function (int $a, int $b): int {
            return $a + $b;
        },
        1,
        2
    );
$report = $benchmark->execute();
echo (string)$report->showReturns();
