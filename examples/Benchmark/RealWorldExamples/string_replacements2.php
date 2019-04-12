<?php declare(strict_types=1);

use AlecRabbit\Tools\Factory;

require_once __DIR__ . '/../../../vendor/autoload.php';

try {
    $benchmark = Factory::createBenchmark();
    $benchmark
        ->withComment('sprintf')
        ->addFunction(
            function ($a) {
                return sprintf('>>>%s<<<', $a);
            },
            '222'
        );
    $benchmark
        ->withComment('str_replace')
        ->addFunction(
            function ($a) {
                return str_replace('%s', $a, '>>>%s<<<');
            },
            '222'
        );
    echo $benchmark
        ->withComment('Comparing...')
        ->report();
} catch (Exception $e) {
    echo 'Error occurred: ';
    echo $e->getMessage() . PHP_EOL;
}
