<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\ProfilerReport;
use PHPUnit\Framework\TestCase;

class ProfilerReportTest extends TestCase
{
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
        $this->assertStringNotContainsString(Strings::MIN, $str);
        $this->assertStringNotContainsString(Strings::MAX, $str);
    }
}
