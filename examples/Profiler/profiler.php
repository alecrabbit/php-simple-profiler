<?php

use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Factory;

const NAME = 'new';
require_once __DIR__ . '/../../vendor/autoload.php';

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
$report = $profiler->getReport();
dump($report); // symfony/var-dumper function dump()
echo PHP_EOL;

echo $report . PHP_EOL;
