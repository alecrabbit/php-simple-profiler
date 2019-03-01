<?php declare(strict_types=1);

use AlecRabbit\Tools\BenchmarkSymfonyPB as BenchmarkWithSymfonyProgressBar;
use function AlecRabbit\tag;

require_once __DIR__ . '/../../../vendor/autoload.php';

const EMPTY_ELEMENTS = ['', null, false];

$args = [
    [1, 2, 3, 4, 5, 6, 7, 8, 9, null, 0],
    3,
];
$iterations = 1000000;

$b = new BenchmarkWithSymfonyProgressBar($iterations);
$o = $b->getOutput();
$o->writeln(tag('Comparing 3 slightly different implementations of function.', 'comment'));
$b->withComment('Using closure')->addFunction('formatted_array_2', ...$args);
$b->withComment('Basic implementation')->addFunction('formatted_array_1', ...$args);
$b->withComment('Using internal functions')->addFunction('formatted_array_3', ...$args);
echo $b->run()->report();

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
    if ($callback) {
        \array_walk($data, $callback);
    }
    $maxLength = arr_el_max_length($data);
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
    if ($callback) {
        \array_walk($data, $callback);
    }
    $maxLength = arr_el_max_length($data);
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
    if ($callback) {
        \array_walk($data, $callback);
    }
    $maxLength = arr_el_max_length($data);
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

function arr_el_max_length(array &$data): int
{
    $maxLength = 0;
    foreach ($data as &$element) {
        if (\is_array($element)) {
            throw new \RuntimeException('Array to string conversion');
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
