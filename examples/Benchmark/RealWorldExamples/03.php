<?php declare(strict_types=1);

use AlecRabbit\Tools\Factory;

require_once __DIR__ . '/../../../vendor/autoload.php';

try {
    $b = Factory::createBenchmark();
    $b
        ->withComment('floatval(intval($value))')
        ->addFunction(
            function ($value) {
                if (is_float($value) && floatval(intval($value)) === $value) {
                    return "$value.0";
                }
            },
            1.0
        );

    $b
        ->withComment('(float)(int)$value')
        ->addFunction(
            function ($value) {
                if (is_float($value) && (float)(int)$value === $value) {
                    return "$value.0";
                }
            },
            1.0
        );
    echo $b->showReturns()->report();
    echo $b->stat();
} catch (Exception $e) {
    echo 'Error occurred: ';
    echo $e->getMessage() . PHP_EOL;
}