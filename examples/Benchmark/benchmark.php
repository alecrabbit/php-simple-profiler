<?php declare(strict_types=1);

use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkOptions;
use NunoMaduro\Collision\Provider;
use const AlecRabbit\Helpers\Strings\Constants\BYTES_UNITS;

require_once __DIR__ . '/../../vendor/autoload.php';

(new Provider)->register(); // Optional line - error handling

$themes = new Themes();
echo $themes->comment('Benchmark example') . PHP_EOL;
echo $themes->dark('PHP version: ' . PHP_VERSION) . PHP_EOL;

$options = new BenchmarkOptions();

$benchmark = new Benchmark($options);
$benchmark
    ->withComment('Benchmark hrtime')
    ->add('hrtime', true);
$benchmark
    ->withComment('Benchmark hrtime')
    ->add('max', [2, 3, 4, 5, 5, 3, 3, 3, 5, 56, 7, 3, 23, 3, 5, 6, 7, 76, 3, 3, 6, 7, 7, 3, 3, 2, 2]);
$benchmark
    ->withComment('Benchmark hash md5')
    ->add('hash', 'md5', 'Hello World!');
$benchmark
    ->withComment('Benchmark hash sha1')
    ->add('hash', 'sha1', 'Hello World!');

$unit = 'mb';
$units = \array_keys(BYTES_UNITS);
$benchmark
    ->withComment('Using in_array()')
    ->add(
        function ($unit) use ($units) {
            return
                \in_array(\strtoupper($unit), $units, true);
        },
        $unit
    );
$benchmark
    ->withComment('Using array_search() [bad example]')
    ->add(
        function ($unit) use ($units) {
            return
                false !== \array_search(\strtoupper($unit), $units, true);
        },
        $unit
    );
$benchmark
    ->withComment('Using array_key_exists()')
    ->add(
        function ($unit) {
            return
                \array_key_exists(\strtoupper($unit), BYTES_UNITS);
        },
        $unit
    );

//$benchmark
//    ->withComment('Benchmark microtime')
//    ->add('microtime', true);
//$benchmark
//    ->withComment('Use sprintf')
//    ->withName('sprintf')
//    ->add(
//        function ($a) {
//            return sprintf(
//                '%s - %s%s%s%s%s%s%s%s',
//                $a,
//                '1',
//                '2',
//                '3',
//                '4',
//                '5',
//                '6',
//                '7',
//                '8'
//            );
//        },
//        '222'
//    );
//$benchmark
//    ->withComment('Concatenate values')
//    ->withName('concat')
//    ->add(
//        function ($a) {
//            return $a . ' - ' . '1' . '2' . '3' . '4' . '5' . '6' . '7' . '8';
//        },
//        '222'
//    );
//$benchmark
//    ->withComment('Just returning value')
////    ->withName('return_value')
//    ->add(
//        function ($a) {
//            return $a;
//        },
//        '222'
//    );
//$func = static function (int $a) {
//    return $a;
//};
//$benchmark
//    ->withComment('Just returning value')
//    ->add(
//        static function () use ($func) {
//            $a = hrtime(true);
//            $func('1');
//            $b =  hrtime(true) - $a;
//        }
//    );
$report = $benchmark->run();
echo $report->withReturns() . PHP_EOL;
//dump($report);
