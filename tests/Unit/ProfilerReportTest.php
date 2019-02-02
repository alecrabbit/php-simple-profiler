<?php

declare(strict_types=1);

namespace Tests\Unit;

use AlecRabbit\Tools\Contracts\StringsInterface;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\ProfilerReport;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class ProfilerReportTest extends TestCase
{
    /** @test */
    public function profilerReport(): void
    {
        $profiler = new Profiler();
        $name = 'new';
        $profiler->counter($name)->bump();
        $profiler->counter()->bump();
        $profiler->timer($name)->check();
        sleep(1);
        $profiler->timer($name)->check();
        $profiler->timer()->check();
        sleep(1);
        $profiler->timer()->check();
        $this->assertEquals(1, $profiler->counter($name)->getValue());

        $this->assertIsString($profiler->timer($name)->elapsed());
        $this->assertEquals('2.0s', $profiler->timer()->elapsed());
        $this->assertEquals('2.0s', $profiler->timer($name)->elapsed());
        $profiler->timer($name, 'vol', 'buy', 'tor');
        $report = $profiler->getReport();
        $this->assertInstanceOf(ProfilerReport::class, $report);

        /** @var ProfilerReport $report */
        $reports = $report->getReports();
        $this->assertIsArray($reports);

        $str = (string)$report;
        $this->assertContains($name, $str);
        $this->assertContains(StringsInterface::COUNTER, $str);
        $this->assertContains(StringsInterface::TIMER, $str);
        $this->assertContains(StringsInterface::ELAPSED, $str);
    }

}