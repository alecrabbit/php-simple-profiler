<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 15:46
 */

namespace Tests\Unit;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class BenchmarkTest extends TestCase
{
    /** @var Benchmark */
    private $bench;

    /** @test */
    public function instance(): void
    {
        $this->assertInstanceOf(Benchmark::class, $this->bench);
        $this->assertInstanceOf(BenchmarkReport::class, $this->bench->getReport());
    }

    /** @test */
    public function addFunctionWithComment(): void
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
        $this->bench
            ->color()
            ->run();
        $report = (string)$this->bench->getReport();
//        dump($report);
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
    public function addFunctionWithNameException(): void
    {
        $bench = new Benchmark(100000);

        $bench
            ->addFunction(function () {
                usleep(100);
                return 1;
            });
        $bench
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $bench
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $bench
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $bench
            ->addFunction(
                function () {
                    throw new \Exception('Simulated Exception');
                }
            );

        $bench
            ->returnResults()
            ->color()
            ->verbose()
            ->run(true);
        $report = $bench->getReport();
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $report = (string)$report;
        $this->assertIsString($report);
        $this->assertContains('Timer:', $report);
        $this->assertContains('Average:', $report);
        $this->assertContains('Last:', $report);
        $this->assertContains('Min', $report);
        $this->assertContains('Max', $report);
        $this->assertContains('Count:', $report);
        $this->assertContains('Done in', $bench->elapsed());
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
        $this->bench = new Benchmark(10);
    }
}
