<?php declare(strict_types=1);

use AlecRabbit\Tools\Factory;

require_once __DIR__ . '/../../../vendor/autoload.php';

try {
    $benchmark = Factory::createBenchmark();
    $benchmark
        ->withComment('sprintf')
        ->addFunction(
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
        ->withComment('concat')
        ->addFunction(
            function ($a) {
                return $a . ' - ' . '1' . '2' . '3' . '4' . '5' . '6' . '7' . '8';
            },
            '222'
        );
    echo $benchmark
        ->withComment('Comparing...')
        ->report();
    echo $benchmark
        ->stat();

//    dump($benchmark);
} catch (Exception $e) {
    echo 'Error occurred: ';
    echo $e->getMessage() . PHP_EOL;
}

