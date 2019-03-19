<?php declare(strict_types=1);

use AlecRabbit\Tools\Factory;

require_once __DIR__ . '/../../../vendor/autoload.php';

try {
    $benchmark = Factory::createBenchmark();
    $benchmark
        ->withComment('Direct call')
        ->addFunction(
            function () {
                return hrtime(true);
            }
        );
    $benchmark
        ->withComment('Direct call(with typecasting)')
        ->addFunction(
            function () {
                return (int)hrtime(true);
            }
        );
    $t = new class
    {
        public function current()
        {
            return hrtime(true);
        }
    };
    $benchmark
        ->withComment('Method call')
        ->addFunction(
            function () use ($t) {
                return $t->current();
            }
        );
    $t2 = new class
    {
        public function current(): int
        {
            return (int)hrtime(true);
        }
    };
    $benchmark
        ->withComment('Method call(with typecasting)')
        ->addFunction(
            function () use ($t2) {
                return $t2->current();
            }
        );
    $t3 = new class
    {
        protected $func = 'hrtime';

        public function current(): int
        {
            return (int)($this->func)(true);
        }
    };
    $benchmark
        ->withComment('Method call use callable (with typecasting)')
        ->addFunction(
            function () use ($t3) {
                return $t3->current();
            }
        );
    echo $benchmark
        ->withComment('Comparing...')
        ->report();
} catch (Exception $e) {
    echo 'Error occurred: ';
    echo $e->getMessage() . PHP_EOL;
}
