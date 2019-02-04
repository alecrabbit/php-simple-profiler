<?php

use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Reports\Factory;

require_once __DIR__ . '/../vendor/autoload.php';

$counter = new Counter();
$counter2 = new Counter('Added', 1, 12);
$counter2->setStep(2);

$counter->bump();
$counter2->bump();
$counter2->bump();
$counter2->bumpBack();


dump($counter->getReport()); // use var_dump
echo PHP_EOL;
dump($counter2->getReport()); // use var_dump
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
