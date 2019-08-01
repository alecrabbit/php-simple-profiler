<?php declare(strict_types=1);

use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Internal\BenchmarkOptions as Options;
use NunoMaduro\Collision\Provider;

require_once __DIR__ . '/../../vendor/autoload.php';

(new Provider)->register(); // Optional line - error handling

// Optional
$themes = new Themes();
echo $themes->comment('Benchmark example') . PHP_EOL;
echo $themes->dark('PHP version: ' . PHP_VERSION) . PHP_EOL;

// Benchmarking
$options = new Options(); // Example
//$options->setMethod(Options::DIRECT_MEASUREMENTS);

const EMPTY_ELEMENTS = ['', null, false];

$args = [
    [1, 2, 3, 4, 5, 6, 7, 8, 9, null, 0],
    3,
    static function (&$item, $key) {
        $item = '[' . $key . '] ' . $item;
    },
];

$benchmark = new Benchmark($options);
$benchmark
    ->withComment('Basic implementation(some code duplication)')
    ->add('formatted_array_1', ...$args);
$benchmark
    ->withComment('Using closure')
    ->add('formatted_array_2', ...$args);
$benchmark
    ->withComment('Using internal functions')
    ->add('formatted_array_3', ...$args);

$report = $benchmark->run();
echo $report . PHP_EOL; // cast BenchmarkReport object to string

//dump($report); // Optional

/*
 * Functions
 */

function formatted_array_1(
    array $data,
    int $columns = 10,
    ?callable $callback = null,
    int $pad = STR_PAD_RIGHT
): array {
    $result = [];
    $maxLength = arr_el_max_length($data, $callback);
    $tmp = [];
    $rowEmpty = true;
    foreach ($data as $element) {
        $tmp[] = \str_pad((string)$element, $maxLength, ' ', $pad);
        $rowEmpty = $rowEmpty && \in_array($element, EMPTY_ELEMENTS, true);
        if (\count($tmp) >= $columns) {
            $result[] = \implode($rowEmpty ? '' : ' ', $tmp);
            $rowEmpty = true;
            $tmp = [];
        }
    }
    if (!empty($tmp)) {
        $result[] = \implode($rowEmpty ? '' : ' ', $tmp);
    }
    return $result;
}

function formatted_array_2(
    array $data,
    int $columns = 10,
    ?callable $callback = null,
    int $pad = STR_PAD_RIGHT
): array {
    $result = [];
    $func = function (&$rowEmpty, &$tmp, &$result) {
        $result[] = \implode($rowEmpty ? '' : ' ', $tmp);
        $rowEmpty = true;
        $tmp = [];
    };
//    if ($callback) {
//        \array_walk($data, $callback);
//    }
    $maxLength = arr_el_max_length($data, $callback);
    $tmp = [];
    $rowEmpty = true;
    foreach ($data as $element) {
        $tmp[] = \str_pad((string)$element, $maxLength, ' ', $pad);
        $rowEmpty &= \in_array($element, EMPTY_ELEMENTS, true);
        if (\count($tmp) >= $columns) {
            $func($rowEmpty, $tmp, $result);
        }
    }
    if (!empty($tmp)) {
        $func($rowEmpty, $tmp, $result);
    }
    return $result;
}

function formatted_array_3(
    array $data,
    int $columns = 10,
    ?callable $callback = null,
    int $pad = STR_PAD_RIGHT
): array {
    $result = $tmp = [];
    $maxLength = arr_el_max_length($data, $callback);
    $rowEmpty = true;
    foreach ($data as $element) {
        $tmp[] = \str_pad((string)$element, $maxLength, ' ', $pad);
        $rowEmpty = $rowEmpty && \in_array($element, EMPTY_ELEMENTS, true);
        if (\count($tmp) >= $columns) {
            update_result($result, $rowEmpty, $tmp);
        }
    }
    if (!empty($tmp)) {
        update_result($result, $rowEmpty, $tmp);
    }
    return $result;
}

// internal functions
function arr_el_max_length(array &$data, ?callable $callback = null): int
{
    $maxLength = 0;
    foreach ($data as $key => &$element) {
        if (\is_array($element)) {
            throw new \RuntimeException('Multidimensional arrays is not supported.');
        }
        if ($callback) {
            $callback($element, $key);
        }

        $len = \strlen($element = (string)$element);
        if ($maxLength < $len) {
            $maxLength = $len;
        }
    }
    return $maxLength;
}

function update_result(array &$result, bool &$rowEmpty, array &$tmp)
{
    $result[] = \implode($rowEmpty ? '' : ' ', $tmp);
    $rowEmpty = true;
    $tmp = [];
}
