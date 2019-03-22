<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Timer;
use PHPUnit\Framework\TestCase;

class ProfilerReportTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function wrongReportable(): void
    {
        $report = new ProfilerReport();
        $timer = new Timer();
        $this->expectException(\RuntimeException::class);
        $report->buildOn($timer);
    }
    /**
     * @test
     * @throws \Exception
     */
    public function getReport(): void
    {
        $profiler = new Profiler();
        $profiler->counter()->bump(2);
        /** @var ProfilerReport $report */
        $report = $profiler->report();
        $this->assertInstanceOf(ProfilerReport::class, $report);
        $reports = $report->getReports();
        $this->assertArrayHasKey(Strings::COUNTERS, $reports);
        $this->assertArrayHasKey(Strings::TIMERS, $reports);
        $str = (string)$report;
        // todo strings check move to formatter tests
        $this->assertIsString($str);
//        dump($str);
        $this->assertStringContainsString(Strings::ELAPSED, $str);
        $this->assertStringContainsString(Strings::COUNTER, $str);
        $this->assertStringNotContainsString(Strings::TIMER, $str);
        $this->assertStringNotContainsString(Strings::AVERAGE, $str);
        $this->assertStringNotContainsString(Strings::LAST, $str);
        $this->assertStringNotContainsString(Strings::PROGRESS_BAR_MIN_WIDTH, $str);
        $this->assertStringNotContainsString(Strings::PROGRESS_BAR_MAX_WIDTH, $str);
    }
}
