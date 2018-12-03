<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 15:46
 */

namespace Tests\Unit;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use AlecRabbit\Tools\Timer;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * @group time-sensitive
 */
class BenchmarkTest extends TestCase
{
    /** @var Benchmark */
    private $bench;

    public static function setUpBeforeClass(): void
    {
        ClockMock::register(Timer::class);
        ClockMock::withClockMock(true);
    }

    public static function tearDownAfterClass(): void
    {
        ClockMock::withClockMock(false);
    }

    /** @test */
    public function instance(): void
    {
        $this->assertInstanceOf(Benchmark::class, $this->bench);
        $this->assertInstanceOf(BenchmarkReport::class, $this->bench->report());
    }

//    /** @test */
//    public function addFunctionWithName(): void
//    {
//        $this->assertInstanceOf(Benchmark::class, $this->bench);
//        $this->bench
//            ->withComment('Added First(1)')
//            ->addFunction(function () {
//                usleep(498);
//            });
//        $this->bench
//            ->withComment('Added Second(3)')
//            ->addFunction(function () {
//                usleep(549);
//            });
//        $this->bench
//            ->withComment('Added Third(2)')
//            ->addFunction(function () {
//                usleep(521);
//            });
//        $this->bench
//            ->withComment('Added Third(2)')
//            ->addFunction(function () {
//                usleep(521);
//            });
//        $this->bench->compare();
//        $expected =
//            [
//                'Added First(1)' => '100.0% (0.498ms)',
//                'Added Third(2)' => '104.6% (0.521ms)',
//                'Added Third(2)_1' => '104.6% (0.521ms)',
//                'Added Second(3)' => '110.2% (0.549ms)',
//            ];
//        $this->assertEquals($expected, $this->bench->report());
//        $expected =
//            [
//                'timers' =>
//                    [
//                        'Added First(1)' =>
//                            [
//                                'name' => 'Added First(1)',
//                                'last' => '0.498ms',
//                                'extended' =>
//                                    [
//                                        'last' => '0.498ms',
//                                        'avg' => '0.498ms',
//                                        'min' => '0.498ms',
//                                        'max' => '0.498ms',
//                                        'count' => 1000,
//                                    ],
//                            ],
//                        'Added Second(3)' =>
//                            [
//                                'name' => 'Added Second(3)',
//                                'last' => '0.549ms',
//                                'extended' =>
//                                    [
//                                        'last' => '0.549ms',
//                                        'avg' => '0.549ms',
//                                        'min' => '0.549ms',
//                                        'max' => '0.549ms',
//                                        'count' => 1000,
//                                    ],
//                            ],
//                        'Added Third(2)' =>
//                            [
//                                'name' => 'Added Third(2)',
//                                'last' => '0.521ms',
//                                'extended' =>
//                                    [
//                                        'last' => '0.521ms',
//                                        'avg' => '0.521ms',
//                                        'min' => '0.521ms',
//                                        'max' => '0.521ms',
//                                        'count' => 1000,
//                                    ],
//                            ],
//                        'Added Third(2)_1' =>
//                            [
//                                'name' => 'Added Third(2)_1',
//                                'last' => '0.521ms',
//                                'extended' =>
//                                    [
//                                        'last' => '0.521ms',
//                                        'avg' => '0.521ms',
//                                        'min' => '0.521ms',
//                                        'max' => '0.521ms',
//                                        'count' => 1000,
//                                    ],
//                            ],
//                    ],
//            ];
//        $this->assertEquals($expected, $this->bench->profilerReport());
//    }

    /** @test */
    public function addFunctionWithName(): void
    {
        $this->assertInstanceOf(Benchmark::class, $this->bench);
        $this->bench
            ->withComment('Added First(1)')
            ->addFunction(function () {
                usleep(498);
            });
        $this->bench
            ->withComment('Added Second(3)')
            ->addFunction(function () {
                usleep(549);
            });
        $this->bench
            ->withComment('Added Third(2)')
            ->addFunction(function () {
                usleep(521);
            });
        $this->bench
            ->withComment('Added Fours(2)')
            ->addFunction(function () {
                usleep(521);
            });
        $this->bench->compare();
//        $expected =
//            """
//Counter:[default_name] Value: 0, Step: 1\n
//Timer:[default_name] Elapsed: 2089.024ms\n
//Timer:[⟨0⟩ Closure::__invoke] Average: 0.498ms, Last: 0.498ms, Min(0): 0.498ms, Max(0): 0.498ms, Count: 1000\n
//Timer:[⟨1⟩ Closure::__invoke] Average: 0.549ms, Last: 0.549ms, Min(0): 0.549ms, Max(0): 0.549ms, Count: 1000\n
//Timer:[⟨2⟩ Closure::__invoke] Average: 0.521ms, Last: 0.521ms, Min(0): 0.521ms, Max(0): 0.521ms, Count: 1000\n
//Timer:[⟨3⟩ Closure::__invoke] Average: 0.521ms, Last: 0.521ms, Min(0): 0.521ms, Max(0): 0.521ms, Count: 1000\n
//Benchmark:\n
//+0.0% (0.498ms) [0] Closure::__invoke() "Added First(1)"\n
//+4.6% (0.521ms) [2] Closure::__invoke() "Added Third(2)"\n
//+4.6% (0.521ms) [3] Closure::__invoke() "Added Fours(2)"\n
//+10.2% (0.549ms) [1] Closure::__invoke() "Added Second(3)"\n
//""";
//        $actual = $this->bench->report();
//        var_export($actual);
        $this->assertInstanceOf(BenchmarkReport::class, $this->bench->report());
    }
//
//    /** @test */
//    public function wrongArgument(): void
//    {
//        $this->expectException(\InvalidArgumentException::class);
//        $this->bench
//            ->withComment('Added Third(2)')
//            ->addFunction('notCallable');
//    }
//
    protected function setUp()
    {
        parent::setUp();
        $this->bench = new Benchmark();
    }
}
