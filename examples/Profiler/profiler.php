<?php

use AlecRabbit\Tools\Profiler;

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
    // symfony/var-dumper function dump()
    // dump($profiler);
    // dump($report);
    echo PHP_EOL;

    echo $report . PHP_EOL;
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
