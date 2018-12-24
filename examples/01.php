<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 17:13
 */

use AlecRabbit\Tools\Benchmark;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * There are moments when you have to choose between two or more different approaches
 * Benchmark class is to help you choose which is faster :)
*/

$benchmark = new Benchmark(50000);

$benchmark
    ->withComment('floatval()')
    ->addFunction('floatval', '3.5');

$benchmark
    ->withComment('intval()')
    ->addFunction('intval', '3');


$benchmark
    ->withComment('(float)')
    ->addFunction(function () {
        return (float)'3.5';
    });

$benchmark
    ->withComment('float "+"')
    ->addFunction(function () {
        return +'3.5';
    });

$benchmark
    ->withComment('(int)')
    ->addFunction(function () {
        return (int)'3';
    });

$benchmark
    ->withComment('int "+"')
    ->addFunction(function () {
        return +'3';
    });

$benchmark
    ->returnResults()
    ->verbose()
    ->color()
    ->run(true);
echo $benchmark->elapsed() . PHP_EOL;

// approx. output:
//
//Running benchmarks(50000):
//............................................................
//
//Benchmark:
//300.3ns (+0.0%) ⟨3⟩ Closure::__invoke() "float "+""
//return: float "3.5"
//306.2ns (+2.0%) ⟨5⟩ Closure::__invoke() "int "+""
//return: integer "3"
//311.4ns (+3.7%) ⟨4⟩ Closure::__invoke() "(int)"
//return: integer "3"
//324.5ns (+8.1%) ⟨2⟩ Closure::__invoke() "(float)"
//return: float "3.5"
//334ns (+11.2%) ⟨1⟩ intval(string) "intval()"
//return: integer "3"
//357ns (+18.9%) ⟨0⟩ floatval(string) "floatval()"
//return: float "3.5"
//
//Counter: 6(1)
//Elapsed: 389.5ms
//Timer:[⟨0⟩ floatval] Average: 357ns, Last: 0ns, Min(2): 0ns, Max(12928): 39.8μs, Count: 50000
//Timer:[⟨1⟩ intval] Average: 334ns, Last: 0ns, Min(5): 0ns, Max(29233): 31μs, Count: 50000
//Timer:[⟨2⟩ Closure::__invoke] Average: 324.5ns, Last: 953.7ns, Min(2): 0ns, Max(12620): 16.9μs, Count: 50000
//Timer:[⟨3⟩ Closure::__invoke] Average: 300.3ns, Last: 0ns, Min(0): 0ns, Max(11123): 17.2μs, Count: 50000
//Timer:[⟨4⟩ Closure::__invoke] Average: 311.4ns, Last: 953.7ns, Min(3): 0ns, Max(10697): 14.1μs, Count: 50000
//Timer:[⟨5⟩ Closure::__invoke] Average: 306.2ns, Last: 953.7ns, Min(3): 0ns, Max(23307): 62μs, Count: 50000
//
//Done in: 389.533ms
