<?php

use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\OldFactory;

const NAME = 'new';
require_once __DIR__ . '/../../vendor/autoload.php';

try {
    $profiler = new Profiler();

    $profiler->counter(NAME)->bump();
    $profiler->counter()->bump();
    $profiler->counter()->bump(2);

    $profiler->timer()->check();
    usleep(100);
    $profiler->timer()->check();
    usleep(510);

    $profiler->timer(NAME)->start();
    usleep(100);
    $profiler->timer(NAME)->check();
    usleep(100);
    $profiler->timer(NAME)->check();
    $report = $profiler->report();
//    dump($report); // symfony/var-dumper function dump()
    echo PHP_EOL;

    echo $report . PHP_EOL;
    dump($profiler);
} catch (Exception $e) {
    echo $e->getMessage(). PHP_EOL;
}
