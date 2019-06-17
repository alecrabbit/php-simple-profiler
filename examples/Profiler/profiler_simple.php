<?php

const NAME = 'someOperation';

require_once __DIR__ . '/../../vendor/autoload.php';

use AlecRabbit\Tools\Profiler;

function someOperation()
{
    return 1;
}

$profiler = new Profiler();

for ($i = 0; $i < 100; $i++) {
    $profiler->counter()->bump();
    someOperation();
    $profiler->timer()->check();
}

echo $profiler->report();
echo PHP_EOL;
