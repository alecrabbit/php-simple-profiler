<?php

use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Reports\Factory;

require_once __DIR__ . '/../vendor/autoload.php';

$counter = new Counter();
$counter2 = new Counter('new');
$counter2->setStep(2);
$a = ['a', 'b', 'c', 'd', 'f'];
foreach ($a as $item) {
    $counter->bump();
    $counter2->bumpDown();
    $counter2->bumpUp();
    $counter2->bumpWith(2);
}
$counter->bumpWith(2);

Factory::enableColour(true);
dump($counter->getReport()); // symfony/var-dumper function dump()
echo PHP_EOL;
dump($counter2->getReport()); // symfony/var-dumper function dump()
echo PHP_EOL;

echo (string)$counter->getReport();
echo PHP_EOL;
echo (string)$counter2->getReport();
echo PHP_EOL;
$counter->bumpWith(2, true);
echo (string)$counter->getReport(true);
echo PHP_EOL;
