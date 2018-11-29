<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 15:46
 */

namespace Tests\Unit;

use AlecRabbit\Profiler\Timer;
use AlecRabbit\Tools\Benchmark;
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
        $this->assertEquals([], $this->bench->profilerReport());
    }

    /** @test */
    public function addFunctionWithName(): void
    {
        $this->assertInstanceOf(Benchmark::class, $this->bench);
        $this->bench
            ->withName('Added First(1)')
            ->addFunction(function () {
                usleep(498);
            });
        $this->bench
            ->withName('Added Second(3)')
            ->addFunction(function () {
                usleep(549);
            });
        $this->bench
            ->withName('Added Third(2)')
            ->addFunction(function () {
                usleep(521);
            });
        $this->bench
            ->withName('Added Third(2)')
            ->addFunction(function () {
                usleep(521);
            });
        $this->bench->compare();
        $expected =
            [
                'Added First(1)' => '100.0% (0.498ms)',
                'Added Third(2)' => '104.6% (0.521ms)',
                'Added Third(2)_1' => '104.6% (0.521ms)',
                'Added Second(3)' => '110.2% (0.549ms)',
            ];
        $this->assertEquals($expected, $this->bench->report());
        $expected =
            [
                'timers' =>
                    [
                        'Added First(1)' =>
                            [
                                'name' => 'Added First(1)',
                                'last' => '0.498ms',
                                'extended' =>
                                    [
                                        'last' => '0.498ms',
                                        'avg' => '0.498ms',
                                        'min' => '0.498ms',
                                        'max' => '0.498ms',
                                        'count' => 1000,
                                    ],
                            ],
                        'Added Second(3)' =>
                            [
                                'name' => 'Added Second(3)',
                                'last' => '0.549ms',
                                'extended' =>
                                    [
                                        'last' => '0.549ms',
                                        'avg' => '0.549ms',
                                        'min' => '0.549ms',
                                        'max' => '0.549ms',
                                        'count' => 1000,
                                    ],
                            ],
                        'Added Third(2)' =>
                            [
                                'name' => 'Added Third(2)',
                                'last' => '0.521ms',
                                'extended' =>
                                    [
                                        'last' => '0.521ms',
                                        'avg' => '0.521ms',
                                        'min' => '0.521ms',
                                        'max' => '0.521ms',
                                        'count' => 1000,
                                    ],
                            ],
                        'Added Third(2)_1' =>
                            [
                                'name' => 'Added Third(2)_1',
                                'last' => '0.521ms',
                                'extended' =>
                                    [
                                        'last' => '0.521ms',
                                        'avg' => '0.521ms',
                                        'min' => '0.521ms',
                                        'max' => '0.521ms',
                                        'count' => 1000,
                                    ],
                            ],
                    ],
            ];
        $this->assertEquals($expected, $this->bench->profilerReport());
    }

    /** @test */
    public function wrongArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->bench
            ->withName('Added Third(2)')
            ->addFunction('notCallable');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->bench = new Benchmark();
    }
}
