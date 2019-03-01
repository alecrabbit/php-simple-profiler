<?php

use AlecRabbit\Tools\Counter;

require_once __DIR__ . '/../../vendor/autoload.php';

$counter = new Counter();
$counter2 = new Counter('Added', 1, 12);
$counter2->setStep(2);

$counter->bump();
$counter2->bump();
$counter2->bump();
$counter2->bumpBack();


dump($counter->report()); // use var_dump
// AlecRabbit\Tools\Reports\CounterReport {#4
//   #formatter: AlecRabbit\Tools\Reports\Formatters\CounterReportFormatter {#5
//     #report: AlecRabbit\Tools\Reports\CounterReport {#4}
//   }
//   #value: 1
//   #max: 1
//   #min: 0
//   #path: 1
//   #length: 1
//   #initialValue: 0
//   #diff: 1
//   #step: 1
//   #bumpedForward: 1
//   #bumpedBack: 0
//   #started: true
//   #name: "default_name"
//}
echo PHP_EOL;
dump($counter2->report()); // use var_dump
//AlecRabbit\Tools\Reports\CounterReport {#10
//   #formatter: AlecRabbit\Tools\Reports\Formatters\CounterReportFormatter {#13
//     #report: AlecRabbit\Tools\Reports\CounterReport {#10}
//   }
//   #value: 14
//   #max: 16
//   #min: 12
//   #path: 6
//   #length: 18
//   #initialValue: 12
//   #diff: 2
//   #step: 2
//   #bumpedForward: 2
//   #bumpedBack: 1
//   #started: true
//   #name: "Added"
//}
echo PHP_EOL;

echo (string)$counter->report();
echo PHP_EOL;
echo (string)$counter2->report();
echo PHP_EOL;
$counter->bump(2);
echo (string)$counter->report();
echo PHP_EOL;
$counter->bump(10);
echo (string)$counter->report(false); // old report
echo (string)$counter->report();

//$counter->bump(0); // will throw
//$counter->bumpBack(-2); // will throw

// Output:
//Counter: 1
//
//Counter[Added]: Value: 14, Step: 2, Bumped: +2 -1, Path: 6, Length: 18, Diff: 2
//
//Counter: 3
//
//Counter: 3
//Counter: 13
