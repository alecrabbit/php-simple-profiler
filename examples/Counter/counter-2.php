<?php

use AlecRabbit\Tools\ExtendedCounter;
use AlecRabbit\Tools\Reports\OldFactory;

require_once __DIR__ . '/../../vendor/autoload.php';

$counter = new ExtendedCounter('name', null, 2);
$counter->bump();
$counter->bump();
$counter->bumpBack();

echo (string)$counter->getReport();
// Output:
//Counter[name]: Value: 3, Step: 1, Bumped: +2 -1, Path: 3, Length: 5, Diff: 1
