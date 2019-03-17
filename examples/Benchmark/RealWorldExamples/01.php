<?php declare(strict_types=1);

use AlecRabbit\Tools\BenchmarkSymfonyProgressBar;
use function AlecRabbit\tag;
use AlecRabbit\Tools\Internal\BenchmarkFunction;

require_once __DIR__ . '/../../../vendor/autoload.php';

const EMPTY_ELEMENTS = ['', null, false];

$args = [
    [1, 2, 3, 4, 5, 6, 7, 8, 9, null, 0],
    3,
    function (&$item, $key) {
        $item = '[' . $key . '] ' . $item;
    },
];
$iterations = 100000;

try {
    $b = new BenchmarkSymfonyProgressBar($iterations);
    $o = $b->getOutput();
    $o->writeln(tag('Comparing 3 slightly different implementations of function `formatted_array()`.', 'comment'));
    $b->withComment('Using closure')->addFunction('formatted_array_2', ...$args);
    $b->withComment('Basic implementation')->addFunction('formatted_array_1', ...$args);
    $b->withComment('Using internal functions')->addFunction('formatted_array_3', ...$args);
    echo $b->report();
} catch (Exception $e) {
    echo 'Error occurred: ';
    echo $e->getMessage(). PHP_EOL;
}

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
