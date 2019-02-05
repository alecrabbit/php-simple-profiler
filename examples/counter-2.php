<?php

use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Reports\Factory;

require_once __DIR__ . '/../vendor/autoload.php';

$counter = new Counter('name');
$counter->bump();

echo (string)$counter->getReport();
echo PHP_EOL;
