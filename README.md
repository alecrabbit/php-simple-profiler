# Simple profiler

PHP Simple profiler 

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8FA0BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.com/alecrabbit/php-simple-profiler.svg?branch=master)](https://travis-ci.com/alecrabbit/php-simple-profiler)
[![Latest Stable Version](https://poser.pugx.org/alecrabbit/php-simple-profiler/v/stable)](https://packagist.org/packages/alecrabbit/php-simple-profiler)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alecrabbit/php-simple-profiler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alecrabbit/php-simple-profiler/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/alecrabbit/php-simple-profiler/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alecrabbit/php-simple-profiler/?branch=master)
[![Total Downloads](https://poser.pugx.org/alecrabbit/php-simple-profiler/downloads)](https://packagist.org/packages/alecrabbit/php-simple-profiler)
[![Latest Stable Version](https://img.shields.io/packagist/v/alecrabbit/php-simple-profiler.svg)](https://packagist.org/packages/alecrabbit/php-simple-profiler)
[![Latest Unstable Version](https://poser.pugx.org/alecrabbit/php-simple-profiler/v/unstable)](https://packagist.org/packages/alecrabbit/php-simple-profiler)

[![License](https://poser.pugx.org/alecrabbit/php-simple-profiler/license)](https://packagist.org/packages/alecrabbit/php-simple-profiler)
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/alecrabbit/php-simple-profiler.svg)](http://isitmaintained.com/project/alecrabbit/php-simple-profiler "Average time to resolve an issue")
[![Percentage of issues still open](http://isitmaintained.com/badge/open/alecrabbit/php-simple-profiler.svg)](http://isitmaintained.com/project/alecrabbit/php-simple-profiler "Percentage of issues still open")

Progress so far:
- [x] add classes with embedded progress bars
- [ ] separate Counter in two classes - SimpleCounter and ExtendedCounter
- [ ] add memory usage to Benchmark class

Docs for version 0.3.0 and above

### Installation
This package is suggested to be used in dev process for debugging of simple scripts

```bash
composer require --dev alecrabbit/php-simple-profiler
 ```
 
 or if you wish
 
```bash
composer require alecrabbit/php-simple-profiler
 ```
 
 Counter and Timer classes can be useful
 
### Usage
##### Quickstart
```php
use AlecRabbit\Tools\BenchmarkSymfonyPB;

require_once __DIR__ . '/vendor/autoload.php';

$benchmark = new BenchmarkSymfonyPB(900000);
$benchmark
    ->addFunction('hrtime', true); 
$benchmark
    ->addFunction('microtime', true);
echo $benchmark->run()->getReport() . PHP_EOL;
echo $benchmark->elapsed() . PHP_EOL;
```
##### For more details see [examples](https://github.com/alecrabbit/php-simple-profiler/tree/master/examples)
##### Note: Not all examples are up to date... work in progress 

### Benchmark classes
 
There are moments when you have to choose between two or more different approaches. Benchmark classes is to help you choose which is faster :) 

 * Benchmark
 * BenchmarkSymfonyPB (with progress bar)
 * BenchmarkSimplePB (with progress bar)
 
---
old docs
> ### Profiler
> If you need to count and/or time some repeating operations Profiler class will help you.
> ```php
> $profiler = new Profiler();
> // in loop 
>     $profiler->counter()->bump();
>     $profiler->timer()->check();
> 
> $report = $profiler->getReport();
> ```
> 
> ### Counter
> 
> ### Timer
> 
> 