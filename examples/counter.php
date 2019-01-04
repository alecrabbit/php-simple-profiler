<?php

use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Reports\Factory;

require_once __DIR__ . '/../vendor/autoload.php';

$counter = new Counter('new');
$counter2 = new Counter('new2');
$counter2->setStep(2);
$a = ['a', 'b', 'c', 'd', 'f'];
foreach ($a as $item) {
    $counter->bump();
    $counter2->bumpDown();
    $counter2->bumpUp();
    $counter2->bumpWith(2);
}

Factory::setColour(true);
dump($counter->getReport()); // symfony/var-dumper function dump()
echo PHP_EOL;
dump($counter2->getReport()); // symfony/var-dumper function dump()
echo PHP_EOL;

echo (string)$counter->getReport();
echo PHP_EOL;
echo (string)$counter2->getReport();
echo PHP_EOL;
