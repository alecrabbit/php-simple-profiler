<?php

use AlecRabbit\Tools\Reports\Factory;
use AlecRabbit\Tools\Timer;

require_once __DIR__ . '/../vendor/autoload.php';

$timer = new Timer('new');
$timer->start();
$count = 5;
for ($i = 0; $i < $count; $i++) {
    sleep(1);
    $timer->check();
}

Factory::setColour(true);
dump($timer->getReport()); // symfony/var-dumper function dump()
echo PHP_EOL;

echo (string)$timer->getReport();
echo PHP_EOL;
