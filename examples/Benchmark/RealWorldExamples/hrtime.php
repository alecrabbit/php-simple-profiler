<?php declare(strict_types=1);

use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Tools\Factory;

require_once __DIR__ . '/../../../vendor/autoload.php';

if (PHP_VERSION_ID < 70300) {
    $themes = new Themes();
    echo
        $themes->warning(
            '[WARNING]: On php versions below 7.3 this example uses polyfill functions(~4 times slower)'
        ) . PHP_EOL;
    echo
        $themes->dark(
            'Your version: ' . PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION
        ) . PHP_EOL;
}
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
