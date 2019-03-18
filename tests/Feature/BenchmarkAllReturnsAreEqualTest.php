<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class BenchmarkAllReturnsAreEqualTest extends TestCase
{
    protected const SIMULATION = 'Simulation';
    protected const NOTIFICATION = 'All returns are equal';
    protected const INTEGER_1 = 'integer(1)';
    protected const INTEGER_2 = 'integer(2)';
    protected const INTEGER_3 = 'integer(3)';

    /** @var Benchmark */
    private $bench;

    /** @var BenchmarkReport */
    private $report;

    /** @test */
    public function checkReportInstance(): void
    {
        $this->benchmarkedFourFunctionsWithEqualReturns();
        $this->assertInstanceOf(BenchmarkReport::class, $this->report);
    }

    protected function benchmarkedFourFunctionsWithEqualReturns(): void
    {
        $this->bench
            ->addFunction(function ($a) {
                usleep(10);
                return $a;
            }, 1);
        $this->bench
            ->addFunction(function ($a) {
                usleep(20);
                return $a;
            }, 1);
        $this->bench
            ->addFunction(function ($a) {
                usleep(30);
                return $a;
            }, 1);
        $this->bench
            ->addFunction(function ($a) {
                usleep(40);
                return $a;
            }, 1);
        $this->report = $this->bench->report();
    }

    /** @test */
    public function showsNotificationOnly(): void
    {
        $this->benchmarkedFourFunctionsWithEqualReturns();
        $this->assertTrue($this->bench->isNotShowReturns());
        $this->assertTrue($this->report->isNotShowReturns());
        $str = (string)$this->report;
        $this->assertStringContainsString(self::NOTIFICATION . '.', $str);
        $this->assertStringNotContainsString(self::INTEGER_1, $str);
        $this->assertEquals(0, substr_count($str, self::INTEGER_1));
    }

    /** @test */
    public function showsNotificationAndReturnOnce(): void
    {
        $this->benchmarkedFourFunctionsWithEqualReturns();
        $this->report->showReturns();
        $str = (string)$this->report;
        $this->assertStringContainsString(self::NOTIFICATION . ':', $str);
        $this->assertStringContainsString(self::INTEGER_1, $str);
        $this->assertEquals(1, substr_count($str, self::INTEGER_1));
    }

    /** @test */
    public function showsNoNotificationAndEachReturn(): void
    {
        $this->benchmarkedFourFunctionsWithDiffReturns();
        $this->report->showReturns();
        $str = (string)$this->report;
        $this->assertStringNotContainsString(self::NOTIFICATION, $str);
        $this->assertStringContainsString(self::INTEGER_1, $str);
        $this->assertStringContainsString(self::INTEGER_2, $str);
        $this->assertStringContainsString(self::INTEGER_3, $str);
        $this->assertEquals(1, substr_count($str, self::INTEGER_1));
        $this->assertEquals(2, substr_count($str, self::INTEGER_2));
        $this->assertEquals(1, substr_count($str, self::INTEGER_3));
    }

    protected function benchmarkedFourFunctionsWithDiffReturns(): void
    {
        $this->bench
            ->addFunction(function ($a) {
                usleep(10);
                return $a;
            }, 2);
        $this->bench
            ->addFunction(function ($a) {
                usleep(20);
                return $a;
            }, 1);
        $this->bench
            ->addFunction(function ($a) {
                usleep(30);
                return $a;
            }, 2);
        $this->bench
            ->addFunction(function ($a) {
                usleep(40);
                return $a;
            }, 3);
        $this->report = $this->bench->report();
    }

    /** @test */
    public function showsNoNotificationAndNoReturn(): void
    {
        $this->benchmarkedFourFunctionsWithDiffReturns();
        $str = (string)$this->report;
        $this->assertStringNotContainsString(self::NOTIFICATION, $str);
        $this->assertStringNotContainsString(self::INTEGER_1, $str);
        $this->assertStringNotContainsString(self::INTEGER_2, $str);
        $this->assertStringNotContainsString(self::INTEGER_3, $str);
        $this->assertEquals(0, substr_count($str, self::INTEGER_1));
        $this->assertEquals(0, substr_count($str, self::INTEGER_2));
        $this->assertEquals(0, substr_count($str, self::INTEGER_3));
    }

    /** @test */
    public function showsNoNotificationAndNoReturnOneFunction(): void
    {
        $this->benchmarkedOneFunction();
        $str = (string)$this->report;
        $this->assertStringNotContainsString(self::NOTIFICATION, $str);
        $this->assertStringNotContainsString(self::INTEGER_1, $str);
        $this->assertStringNotContainsString(self::INTEGER_2, $str);
        $this->assertStringNotContainsString(self::INTEGER_3, $str);
        $this->assertEquals(0, substr_count($str, self::INTEGER_1));
        $this->assertEquals(0, substr_count($str, self::INTEGER_2));
        $this->assertEquals(0, substr_count($str, self::INTEGER_3));
    }

    /** @test */
    public function showsNoNotificationAndNoReturnOneFunctionAddedTwo(): void
    {
        $this->benchmarkedOneFunctionAddedTwo();
        $str = (string)$this->report;
        $this->assertStringNotContainsString(self::NOTIFICATION, $str);
        $this->assertStringNotContainsString(self::INTEGER_1, $str);
        $this->assertStringNotContainsString(self::INTEGER_2, $str);
        $this->assertStringNotContainsString(self::INTEGER_3, $str);
        $this->assertStringContainsString(\RuntimeException::class, $str);
        $this->assertStringContainsString(Strings::EXCEPTIONS, $str);
        $this->assertStringContainsString(self::SIMULATION, $str);
        $this->assertEquals(0, substr_count($str, self::INTEGER_1));
        $this->assertEquals(0, substr_count($str, self::INTEGER_2));
        $this->assertEquals(0, substr_count($str, self::INTEGER_3));
    }

    protected function benchmarkedOneFunction(): void
    {
        $this->bench
            ->addFunction(function ($a) {
                usleep(10);
                return $a;
            }, 1);
        $this->report = $this->bench->report();
    }

    protected function benchmarkedOneFunctionAddedTwo(): void
    {
        $this->bench
            ->addFunction(function ($a) {
                usleep(10);
                return $a;
            }, 1);
        $this->bench
            ->addFunction(function () {
                throw new \RuntimeException(self::SIMULATION);
            }, 1);
        $this->report = $this->bench->report();
    }

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->bench = new Benchmark(100);
    }
}
