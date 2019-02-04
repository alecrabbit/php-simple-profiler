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
            ->run();
        $report = $this->bench->getReport();
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertContains('Added First(1)', $str);
        $this->assertContains('Added Second(3)', $str);
        $this->assertContains('Added Third(2)', $str);
        $this->assertContains('Added Fours(2)', $str);
    }

    /** @test */
    public function addFunctionWithNameException(): void
    {
        $bench = new Benchmark(100);

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
            ->run(true);
        $report = $bench->getReport();
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $str = (string)$report;
        $this->assertIsString($str);
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
