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
//        usleep(10);
//        $profiler->timer()->check();
        /** @var ProfilerReport $report */
        $report = $profiler->report();
        $this->assertInstanceOf(ProfilerReport::class, $report);
        $str = (string)$report;
        $this->assertIsString($str);
//        dump($str);
        $this->assertContains(Strings::ELAPSED, $str);
        $this->assertContains(Strings::COUNTER, $str);
        $this->assertNotContains(Strings::TIMER, $str);
        $this->assertNotContains(Strings::AVERAGE, $str);
        $this->assertNotContains(Strings::LAST, $str);
        $this->assertNotContains(Strings::MIN, $str);
        $this->assertNotContains(Strings::MAX, $str);
    }
}
