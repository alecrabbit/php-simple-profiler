# PHP Simple profiler

[![PHP Version](https://img.shields.io/packagist/php-v/alecrabbit/php-simple-profiler.svg)](https://php.net/)
[![Build Status](https://travis-ci.com/alecrabbit/php-simple-profiler.svg?branch=master)](https://travis-ci.com/alecrabbit/php-simple-profiler)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alecrabbit/php-simple-profiler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alecrabbit/php-simple-profiler/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/alecrabbit/php-simple-profiler/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alecrabbit/php-simple-profiler/?branch=master)
[![Total Downloads](https://poser.pugx.org/alecrabbit/php-simple-profiler/downloads)](https://packagist.org/packages/alecrabbit/php-simple-profiler)

[![Latest Stable Version](https://poser.pugx.org/alecrabbit/php-simple-profiler/v/stable)](https://packagist.org/packages/alecrabbit/php-simple-profiler)
[![Latest Version](https://img.shields.io/packagist/v/alecrabbit/php-simple-profiler.svg)](https://packagist.org/packages/alecrabbit/php-simple-profiler)
[![Latest Unstable Version](https://poser.pugx.org/alecrabbit/php-simple-profiler/v/unstable)](https://packagist.org/packages/alecrabbit/php-simple-profiler)

[![License](https://poser.pugx.org/alecrabbit/php-simple-profiler/license)](https://packagist.org/packages/alecrabbit/php-simple-profiler)
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/alecrabbit/php-simple-profiler.svg)](http://isitmaintained.com/project/alecrabbit/php-simple-profiler "Average time to resolve an issue")
[![Percentage of issues still open](http://isitmaintained.com/badge/open/alecrabbit/php-simple-profiler.svg)](http://isitmaintained.com/project/alecrabbit/php-simple-profiler "Percentage of issues still open")

VERSION ^0.4 || ^0.5

### Installation
For now this package is suggested to be used in dev process

```bash
composer require --dev alecrabbit/php-simple-profiler
 ```
 
 or if you wish
 
```bash
composer require alecrabbit/php-simple-profiler
 ```
 
 
#### Quickstart
##### Benchmark
```php
use AlecRabbit\Tools\BenchmarkSymfonyPB;

require_once __DIR__ . '/vendor/autoload.php';

$benchmark = new BenchmarkSymfonyPB(900000);
$benchmark
    ->addFunction('hrtime', true); 
$benchmark
    ->addFunction('microtime', true);
echo $benchmark->run()->getReport() . PHP_EOL;
echo $benchmark->stat() . PHP_EOL;
```
###### For more details see [examples](https://github.com/alecrabbit/php-simple-profiler/tree/master/examples)
> Note: Some examples could be not up to date... WIP

### Benchmark classes
 
There are moments when you have to choose between two or more different approaches. Benchmark classes is to help you choose which is faster :)
 * Benchmark (no default progress bar, silent measurements)
 * BenchmarkSymfonyPB (with Symfony progress bar)
 ```
[=====>------------------------------------------------------]   9% 1 secs/12 sec
```
 * BenchmarkSimplePB (with star progress bar)
  ```
 ******************************
 ```
##### Example
Let's say you want to know which is faster `call_user_func($func)` or `$func()`. First you need to create an instance of Benchmark class
```php
$b = new BenchmarkSymfonyPB(900000) // with Symfony Progress bar, 900000 measurments
``` 
> Note: Xdebug extension slowing things down a lot! Disable it (I'm using two different images [w/o Xdebug](https://github.com/alecrabbit/php-simple-profiler/tree/master/docker-compose.yml) and [with Xdebug](https://github.com/alecrabbit/php-simple-profiler/tree/master/docker-compose-debug.yml))

Then you have to add functions to test. But first let's add a closure:
```php
$func = function (array $a) {
    return array_sum($a);
};
```
Now we are ready to add functions:
```php
$a = [1, 2, 3];

$b->addFunction('call_user_func', $func, $a);

$b->addFunction($func, $a);
```
And now you can run the benchmarking
```php
$b->run();
```
Getting results
```php
$report = $b->getReport(); // you can get report object and use data from it 
echo $report . PHP_EOL; // or you can print it by default formatter
echo $b->stat() . PHP_EOL;
```
Results will be something like that
```
Results:
Benchmark:
1.  219.3ns (  0.00%) $func(array) $func(...$args)
2.  287.4ns ( 31.09%) call_user_func(array) \call_user_func($func, ...$args)
All returns are equal: 
integer(6) 
Benchmarked: 2 
Memory: 0.87MB(0.91MB) Real: 2.00MB(2.00MB)

Done in: 1.1s
```
### Profiler::class
Profiler is a kinda wrapper for Counter and Timer in case if you need them both.
```php
$profiler = new Profiler();

for ($i = 0; $i < 100; $i++) {
    $profiler->counter()->bump();
    someOperation();
    $profiler->timer()->check();
}

echo $profiler->getReport() . PHP_EOL;
```
### Counter::class
// todo 

### Timer::class
// todo 
