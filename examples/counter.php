<?php

use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Reports\Factory;

require_once __DIR__ . '/../vendor/autoload.php';

$counter = new Counter();
$counter2 = new Counter('Added');
$counter2->setStep(2);

$counter->bump();
$counter2->bumpReverse();


var_export($counter->getReport());
echo PHP_EOL;
dump($counter2->getReport()); // symfony/var-dumper function dump()
echo PHP_EOL;

echo (string)$counter->getReport();
echo PHP_EOL;
echo (string)$counter2->getReport();
echo PHP_EOL;
$counter->setStep(2)->bump();
echo (string)$counter->getReport();
echo PHP_EOL;
$counter->setStep(10)->bump();
echo (string)$counter->getReport(false); // old report
echo (string)$counter->getReport();
echo PHP_EOL;
