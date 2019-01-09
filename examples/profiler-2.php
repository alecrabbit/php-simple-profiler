<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Factory;

function someOperation()
{
    usleep(random_int(10, 100));
}
const NAME = 'someOperation';
$profiler = new Profiler();
$profiler->timer(NAME)->start();
for ($i = 0; $i < 100; $i++) {
    someOperation();
    $profiler->counter(NAME)->bump();
    $profiler->timer(NAME)->check();
}

Factory::setColour(true);
//dump($profiler->getReport()); // symfony/var-dumper function dump()
//echo PHP_EOL;

echo (string)$profiler->getReport();
echo PHP_EOL;
