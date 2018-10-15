<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 21:28
 */

namespace Unit;


use AlecRabbit\Profiler\Profiler;
use PHPUnit\Framework\TestCase;

class ProfilerTest extends TestCase
{
    /** @test */
    public function ClassCreation()
    {
        $profiler = new Profiler();
        $this->assertInstanceOf(Profiler::class, $profiler);
    }

    /** @test */
    public function CounterCreation()
    {
        $profiler = new Profiler();
        $profiler->counter('new')->bumpUp();
        $this->assertEquals(1, $profiler->counter('new')->getValue());
        $profiler->counter('new', 'vol', 'buy', 'tor')->bumpUp();
        $this->assertStringMatchesFormat(
            '%s [%s, %s, %s]',
            $profiler
                ->counter('new', 'vol', 'buy', 'tor')
                ->getName()
        );
    }

    /** @test */
    public function TimerCreation()
    {
        $profiler = new Profiler();
        $profiler->counter('new')->bumpUp();
        $profiler->timer('new')->start();
        $profiler->timer('new')->check();
        $this->assertEquals(0.00000214, $profiler->timer('new')->elapsed(), '', 0.001);
        $this->assertStringMatchesFormat(
            '%s [%s, %s, %s]',
            $profiler
                ->timer('new', 'vol', 'buy', 'tor')
                ->getName()
        );
    }


    /** @test */
    public function ProfilerReport()
    {
        $profiler = new Profiler();
        $profiler->counter('new')->bumpUp();
        $profiler->counter()->bump();
        $profiler
            ->timer('new')
            ->forceStart()
            ->check();
        $profiler
            ->timer()
            ->forceStart()
            ->check();
        $this->assertEquals(1, $profiler->counter('new')->getValue());

        $this->assertEquals(0.00000214, $profiler->timer('new')->elapsed(), '', 0.001);
        $profiler
            ->timer('new', 'vol', 'buy', 'tor')
            ->forceStart()->check();
        $report = $profiler->report();
        $report_extended = $profiler->report(true);
        $this->assertInternalType('array', $report);
        $this->assertCount(5, $report);

        $this->assertInternalType('array', $report_extended);
        $this->assertCount(5, $report_extended);

    }
}