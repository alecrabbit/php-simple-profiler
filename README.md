# Simple profiler

PHP Simple profiler 

[![Build Status](https://travis-ci.com/alecrabbit/php-simple-profiler.svg?branch=master)](https://travis-ci.com/alecrabbit/php-simple-profiler)
[![Latest Stable Version](https://poser.pugx.org/alecrabbit/php-simple-profiler/v/stable)](https://packagist.org/packages/alecrabbit/php-simple-profiler)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alecrabbit/php-simple-profiler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alecrabbit/php-simple-profiler/?branch=master)
[![Total Downloads](https://poser.pugx.org/alecrabbit/php-simple-profiler/downloads)](https://packagist.org/packages/alecrabbit/php-simple-profiler)
[![Latest Stable Version](https://img.shields.io/packagist/v/alecrabbit/php-simple-profiler.svg)](https://packagist.org/packages/alecrabbit/php-simple-profiler)
[![Latest Unstable Version](https://poser.pugx.org/alecrabbit/php-simple-profiler/v/unstable)](https://packagist.org/packages/alecrabbit/php-simple-profiler)
[![License](https://poser.pugx.org/alecrabbit/php-simple-profiler/license)](https://packagist.org/packages/alecrabbit/php-simple-profiler)

Profiler

Counter

Timer

### Usage

```php
require_once __DIR__ . '/../vendor/autoload.php';

$profiler = new \AlecRabbit\Profiler\Profiler();

$profiler->counter('new')->bump();
$profiler->counter()->bump();
$profiler->counter()->setStep(2);
$profiler->counter()->bump();
var_export($profiler->report());
echo PHP_EOL;

// array (
//     'counters' =>
//         array (
//             'new' =>
//                 array (
//                     'name' => 'new',
//                     'count' => 1,
//                     'extended' => NULL,
//                 ),
//             'default' =>
//                 array (
//                     'name' => 'default',
//                     'count' => 3,
//                     'extended' => NULL,
//                 ),
//         ),
// )


$profiler->timer()->start();
$profiler->timer()->check();
usleep(100);
$profiler->timer()->check();
usleep(510);

$profiler->timer('new')->start();
$profiler->timer('new')->check();
usleep(100);
$profiler->timer('new')->check();
usleep(510);

var_export($profiler->report(null, true));
echo PHP_EOL;

//    array (
//      'counters' => 
//      array (
//        'new' => 
//        array (
//          'name' => 'new',
//          'count' => 1,
//          'extended' => NULL,
//        ),
//        'default' => 
//        array (
//          'name' => 'default',
//          'count' => 3,
//          'extended' => NULL,
//        ),
//      ),
//      'timers' => 
//      array (
//        'default' => 
//        array (
//          'name' => 'default',
//          'last' => 0.0001590251922607422,
//          'extended' => 
//          array (
//            'last' => 0.0001590251922607422,
//            'avg' => 8.058547973632812E-5,
//            'min' => 2.1457672119140625E-6,
//            'max' => 0.0001590251922607422,
//            'count' => 2,
//          ),
//        ),
//        'new' => 
//        array (
//          'name' => 'new',
//          'last' => 0.00015592575073242188,
//          'extended' => 
//          array (
//            'last' => 0.00015592575073242188,
//            'avg' => 7.843971252441406E-5,
//            'min' => 9.5367431640625E-7,
//            'max' => 0.00015592575073242188,
//            'count' => 2,
//          ),
//        ),
//      ),
//    )

var_export($profiler->report(true));
echo PHP_EOL;

//    array (
//      'counters' => 
//      array (
//        'new' => 
//        array (
//          'name' => 'new',
//          'count' => 1,
//          'extended' => NULL,
//        ),
//        'default' => 
//        array (
//          'name' => 'default',
//          'count' => 3,
//          'extended' => NULL,
//        ),
//      ),
//      'timers' => 
//      array (
//        'default' => 
//        array (
//          'name' => 'default',
//          'last' => '0.159ms',
//          'extended' => NULL,
//        ),
//        'new' => 
//        array (
//          'name' => 'new',
//          'last' => '0.156ms',
//          'extended' => NULL,
//        ),
//      ),
//    )

var_export($profiler->report(true, true));
echo PHP_EOL;

//    array (
//      'counters' => 
//      array (
//        'new' => 
//        array (
//          'name' => 'new',
//          'count' => 1,
//          'extended' => NULL,
//        ),
//        'default' => 
//        array (
//          'name' => 'default',
//          'count' => 3,
//          'extended' => NULL,
//        ),
//      ),
//      'timers' => 
//      array (
//        'default' => 
//        array (
//          'name' => 'default',
//          'last' => '0.159ms',
//          'extended' => 
//          array (
//            'last' => '0.159ms',
//            'avg' => '0.081ms',
//            'min' => '0.002ms',
//            'max' => '0.159ms',
//            'count' => 2,
//          ),
//        ),
//        'new' => 
//        array (
//          'name' => 'new',
//          'last' => '0.156ms',
//          'extended' => 
//          array (
//            'last' => '0.156ms',
//            'avg' => '0.078ms',
//            'min' => '0.001ms',
//            'max' => '0.156ms',
//            'count' => 2,
//          ),
//        ),
//      ),
//    )
```