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
        $this->assertInstanceOf(BenchmarkReport::class, $this->bench->getReport());
    }

    /** @test */
    public function addFunctionWithName(): void
    {
        $this->assertInstanceOf(Benchmark::class, $this->bench);
        $this->bench
            ->withComment('Added First(1)')
            ->addFunction(function () {
                usleep(498);
            }, 1, 2);
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
        $this->bench->run();
        $report = (string)$this->bench->getReport();
        $this->assertInstanceOf(BenchmarkReport::class, $this->bench->getReport());
        $this->assertIsString($report);
        $this->assertContains('Timer:', $report);
        $this->assertContains('Average:', $report);
        $this->assertContains('Last:', $report);
        $this->assertContains('Min', $report);
        $this->assertContains('Max', $report);
        $this->assertContains('Count:', $report);
    }

    /** @test */
    public function wrongArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->bench
            ->withComment('Added Third(2)')
            ->addFunction('notCallable');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->bench = new Benchmark();
    }
}
