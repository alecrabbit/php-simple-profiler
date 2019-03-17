<?php declare(strict_types=1);

use AlecRabbit\Tools\BenchmarkSymfonyProgressBar;
use function AlecRabbit\tag;

require_once __DIR__ . '/../../../vendor/autoload.php';

const ITERATIONS = 50000;

try {
    $b = new BenchmarkSymfonyProgressBar(ITERATIONS);
    $o = $b->getOutput();
    $o->writeln(tag('Comparing...', 'comment'));

    $b
        ->withComment('Direct call')
        ->addFunction(
            function () {
                return hrtime(true);
            }
        );
    $t = new class
    {
        public function current()
        {
            return hrtime(true);
        }
    };
    $b
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
    $b
        ->withComment('Method call(with typecasting)')
        ->addFunction(
            function () use ($t2) {
                return $t2->current();
            }
        );

    echo $b->report();
    echo PHP_EOL;
    echo $b->stat();
} catch (\Throwable $e) {
    echo 'Error occurred: ';
    echo $e->getMessage() . PHP_EOL;
}