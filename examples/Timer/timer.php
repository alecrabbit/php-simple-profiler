<?php

use AlecRabbit\Tools\Reports\Factory;
use AlecRabbit\Tools\Timer;

require_once __DIR__ . '/../vendor/autoload.php';

echo 'Start...', PHP_EOL, 'wait 5 sec', PHP_EOL;
$timer = new Timer('new');
$timer->start();
$count = 5;
for ($i = 0; $i < $count; $i++) {
    sleep(1);
    echo '.';
    $timer->check();
}
echo "\n";
dump($timer->getReport()); // use var_dump
echo PHP_EOL;

echo (string)$timer->getReport();
// Timer:[new] Average: 1s, Last: 1s, Min(~): 1s, Max(~): 1s, Count: 5
echo PHP_EOL;
