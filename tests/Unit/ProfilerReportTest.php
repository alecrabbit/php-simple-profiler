<?php

declare(strict_types=1);

namespace Tests\Unit;

use AlecRabbit\Tools\Contracts\StringConstants;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\CounterReport;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\TimerReport;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class ProfilerReportTest extends TestCase
{
    protected const NAME = 'new';

    /** @test */
    public function profilerReport(): void
    {
        $profiler = new Profiler();
        $profiler->counter(self::NAME)->bump();
        $profiler->counter()->bump();
        $profiler->timer(self::NAME)->check();
        sleep(1);
        $profiler->timer(self::NAME)->check();
        $profiler->timer()->check();
        sleep(1);
        $profiler->timer()->check();
        $this->assertEquals(1, $profiler->counter(self::NAME)->getValue());

        $this->assertIsString($profiler->timer(self::NAME)->elapsed());
        $this->assertEquals('2.0s', $profiler->timer()->elapsed());
        $this->assertEquals('2.0s', $profiler->timer(self::NAME)->elapsed());
        $profiler->timer(self::NAME, 'vol', 'buy', 'tor');
        $report = $profiler->getReport();
        $this->assertInstanceOf(ProfilerReport::class, $report);
        $str = (string)$report;
        $this->assertContains(self::NAME, $str);
        $this->assertContains(StringConstants::COUNTER, $str);
        $this->assertContains(StringConstants::TIMER, $str);
        $this->assertContains(StringConstants::ELAPSED, $str);

        /** @var ProfilerReport $report */
        $reports = $report->getReports();
        $this->assertIsArray($reports);
        $counterReports = $report->getCountersReports();
        $this->assertIsArray($counterReports);
        foreach ($counterReports as $cr) {
            $this->assertInstanceOf(CounterReport::class, $cr);
        }
        $timerReports = $report->getTimersReports();
        $this->assertIsArray($timerReports);
        foreach ($timerReports as $tr) {
            $this->assertInstanceOf(TimerReport::class, $tr);
        }
    }

    /** @test */
    public function profilerReportEmpty(): void
    {
        $profiler = new Profiler();
        $report = $profiler->getReport();
        /** @var ProfilerReport $report */
        $reports = $report->getReports();
        $this->assertIsArray($reports);
        $this->assertCount(2, $reports);
        $counterReports = $report->getCountersReports();
        $this->assertIsArray($counterReports);
        foreach ($counterReports as $cr) {
            $this->assertInstanceOf(CounterReport::class, $cr);
        }
        $timerReports = $report->getTimersReports();
        $this->assertIsArray($timerReports);
        foreach ($timerReports as $tr) {
            $this->assertInstanceOf(TimerReport::class, $tr);
        }
    }
}