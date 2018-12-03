<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 21:28
 */

namespace Unit;

use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\TimerReport;
use AlecRabbit\Tools\Timer;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;


class ProfilerExt extends Profiler
{
    protected function formatName($name, $suffixes): string
    {
        return
            sprintf('%s >> %s <<', $name, implode(', ', $suffixes));
    }
}

class ProfilerExtensionTest extends TestCase
{
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
    public function classCreation(): void
    {
        $profiler = new Profiler();
        $this->assertInstanceOf(Profiler::class, $profiler);
        $this->assertEquals('default_name [new]', $profiler->timer(null, 'new')->getName());
        $this->assertEquals('default_name [new]', $profiler->counter(null, 'new')->getName());
        $profiler = new ProfilerExt();
        $this->assertInstanceOf(ProfilerExt::class, $profiler);
        $this->assertEquals('default_name >> new <<', $profiler->timer(null, 'new')->getName());
        $this->assertEquals('default_name >> new <<', $profiler->counter(null, 'new')->getName());
    }

    /** @test */
    public function multipleCountersCreation(): void
    {
        $profiler = new Profiler();
        $profiler->counter();
        $profiler->counter('new');
        $profiler->counter();
        $profiler->counter('new');
        $profiler->counter();
        $profiler->counter('new');
        /** @var ProfilerReport $report */
        $report = $profiler->report();
        $this->assertArrayHasKey('default_name', $report->getReports()[Profiler::_COUNTERS]);
        $this->assertArrayHasKey('new', $report->getReports()[Profiler::_COUNTERS]);
        $this->assertArrayHasKey('default_name', $report->getReports()[Profiler::_TIMERS]);
    }
    
    /** @test */
    public function multipleTimersCreation(): void
    {
        $profiler = new Profiler();
        $profiler->timer();
        $profiler->timer('new');
        $profiler->timer();
        $profiler->timer('new');
        $profiler->timer();
        $profiler->timer('new');
        /** @var ProfilerReport $report */
        $report = $profiler->report();
        $this->assertArrayHasKey('default_name', $report->getReports()[Profiler::_COUNTERS]);
        $this->assertArrayHasKey('default_name', $report->getReports()[Profiler::_TIMERS]);
        $this->assertArrayHasKey('new', $report->getReports()[Profiler::_TIMERS]);

        foreach ($report->getReports()[Profiler::_TIMERS] as $item) {
            $this->assertInstanceOf(TimerReport::class, $item);
        }
    }
}

