<?php declare(strict_types=1);

use AlecRabbit\Tools\Factory;

require_once __DIR__ . '/../../../vendor/autoload.php';

try {
    $benchmark = Factory::createBenchmark();
    $a = [1, 1, null, 4, 4, 6, 34, 12, 4, 6, 7, 8, 3, '6', 7689, 56, 23, 3, '7', 89, null, 64, 65, 45, null, 5];
    $c = new class
    {
        public function process(array $a): array
        {
            return
                array_filter(
                    $a,
                    function ($val): bool {
                        return $val !== null;
                    }
                );
        }
    };
    $s = new class
    {
        public function process(array $a): array
        {
            return
                array_filter(
                    $a,
                    static function ($val): bool {
                        return $val !== null;
                    }
                );
        }
    };
    $benchmark
        ->withComment('non-static')
        ->addFunction(
            function ($a) use ($c) {
                return $c->process($a);
            },
            $a
        );
    $benchmark
        ->withComment('static')
        ->addFunction(
            function ($a) use ($s) {
                return $s->process($a);
            },
            $a
        );
    echo $benchmark
        ->withComment('Comparing...')
        ->report();
} catch (Exception $e) {
    echo 'Error occurred: ';
    echo $e->getMessage() . PHP_EOL;
}