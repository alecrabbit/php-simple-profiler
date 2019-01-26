<?php

use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Factory;

require_once __DIR__ . '/../vendor/autoload.php';

$profiler = new Profiler();

$profiler->counter('new')->bump();
$profiler->counter()->bump();
$profiler->counter()->setStep(2);
$profiler->counter()->bump();

$profiler->timer()->check();
usleep(100);
$profiler->timer()->check();
usleep(510);

$profiler->timer('new')->start();
usleep(100);
$profiler->timer('new')->check();
usleep(100);
$profiler->timer('new')->check();
usleep(5510);

Factory::enableColour(true);
dump($profiler->getReport()); // symfony/var-dumper function dump()
echo PHP_EOL;

echo (string)$profiler->getReport();
echo PHP_EOL;
