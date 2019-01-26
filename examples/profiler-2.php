<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Factory;

function someOperation()
{
    usleep(random_int(1, 100));
}
const NAME = 'someOperation';
$profiler = new Profiler();
$timer = $profiler->timer(NAME);
$counter = $profiler->counter(NAME);
$timer->start();
for ($i = 0; $i < 100; $i++) {
    $counter->bump();
    someOperation();
    $timer->check();
}

Factory::enableColour(true);
//dump($profiler->getReport()); // symfony/var-dumper function dump()
//echo PHP_EOL;

echo $profiler->getReport();
echo PHP_EOL;
