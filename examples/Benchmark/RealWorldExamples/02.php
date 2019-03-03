<?php declare(strict_types=1);

use const AlecRabbit\Helpers\Strings\Constants\BYTES_UNITS;
use AlecRabbit\Tools\BenchmarkSymfonyPB as BenchmarkWithSymfonyProgressBar;
use function AlecRabbit\tag;

require_once __DIR__ . '/../../../vendor/autoload.php';

$iterations = 1000000;

$benchmark = new BenchmarkWithSymfonyProgressBar($iterations);

$o = $benchmark->getOutput();

$o->writeln(tag('Comparing `array_key_exists`, `in_array` and `array_search`.', 'comment'));

$unit = 'mb';
$units = \array_keys(BYTES_UNITS);
$benchmark
    ->withComment('Using in_array()')
    ->addFunction(
        function ($unit) use ($units) {
            return
                \in_array(\strtoupper($unit), $units, true);
        },
        $unit
    );
$benchmark
    ->withComment('Using array_search() [bad example]')
    ->addFunction(
        function ($unit) use ($units) {
            return
                false !== \array_search(\strtoupper($unit), $units, true);
        },
        $unit
    );
$benchmark
    ->withComment('Using array_key_exists()')
    ->addFunction(
        function ($unit) {
            return
                \array_key_exists(\strtoupper($unit), BYTES_UNITS);
        },
        $unit
    );

echo $benchmark->run()->report();
