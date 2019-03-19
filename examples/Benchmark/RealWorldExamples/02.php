<?php declare(strict_types=1);

use AlecRabbit\Tools\Factory;
use const AlecRabbit\Helpers\Strings\Constants\BYTES_UNITS;

require_once __DIR__ . '/../../../vendor/autoload.php';

try {
    $b = Factory::createBenchmark(1000, false);
    $unit = 'mb';
    $units = \array_keys(BYTES_UNITS);
    $b
        ->withComment('Using in_array()')
        ->addFunction(
            function ($unit) use ($units) {
                return
                    \in_array(\strtoupper($unit), $units, true);
            },
            $unit
        );
    $b
        ->withComment('Using array_search() [bad example]')
        ->addFunction(
            function ($unit) use ($units) {
                return
                    false !== \array_search(\strtoupper($unit), $units, true);
            },
            $unit
        );
    $b
        ->withComment('Using array_key_exists()')
        ->addFunction(
            function ($unit) {
                return
                    \array_key_exists(\strtoupper($unit), BYTES_UNITS);
            },
            $unit
        );
    echo $b->withComment('Comparing...')->showReturns()->report();
    echo $b->stat();
} catch (Exception $e) {
    echo 'Error occurred: ';
    echo $e->getMessage() . PHP_EOL;
}

