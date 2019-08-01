<?php declare(strict_types=1);

// Prepare args for benchmarks
$real = [
    1, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4,
    3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3,
    1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3,
    5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2,
    2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5,
    1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3,
    3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1,
    1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5,
    5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2,
    5, 6, 3, 2, 2, 3, 4, 5, 5, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
];

$real2 = [
    1, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4,
    3, 3, 2, 2, 3, 1, 1, 3, 3, 3, 5, 5, 2, 2, 2, 2, 5, 6, 3, 2, 2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
    2, 3, 4, 5, 5, 1, 2, 3, 4, 3, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 3, 2, 5,
];

echo PHP_EOL . 'Size of $real:' . count($real) . PHP_EOL;
echo 'Size of $real2:' . count($real2) . PHP_EOL . PHP_EOL;
