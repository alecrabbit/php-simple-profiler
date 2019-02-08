<?php

use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Reports\Factory;

require_once __DIR__ . '/../../vendor/autoload.php';

$counter = new Counter('name', null, 2);
$counter->bump();
$counter->bump();
$counter->bumpBack();

echo (string)$counter->getReport();
// Output:
//Counter[name]: Value: 3, Step: 1, Bumped: +2 -1, Path: 3, Length: 5, Diff: 1
