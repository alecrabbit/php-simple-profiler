<?php

const NAME = 'someOperation';

require_once __DIR__ . '/../../vendor/autoload.php';

use AlecRabbit\Tools\Profiler;

function someOperation()
{
    usleep(random_int(1, 100));
}

$profiler = new Profiler();
$timer = $profiler->timer(NAME);
$timer2 = $profiler->timer('new', 'and', 'suffixes');
$counter = $profiler->counter(NAME, 'and', 'suffixes');
$timer->start();
for ($i = 0; $i < 100; $i++) {
    $counter->bump();
    $start = microtime(true);
    someOperation();
    $stop = microtime(true);
    $timer->check();
    $timer2->bounds($start, $stop, $i);
}

//dump($profiler->getReport()); // symfony/var-dumper function dump()
//echo PHP_EOL;
echo 'First report' ;
echo PHP_EOL;
echo $profiler->getReport();
echo PHP_EOL;
echo PHP_EOL;

$profiler = new Profiler();

echo 'Second report' ;
echo PHP_EOL;
echo $profiler->getReport();
echo PHP_EOL;
