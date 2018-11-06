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
    public function ClassCreation(): void
    {
        $profiler = new Profiler();
        $this->assertInstanceOf(Profiler::class, $profiler);
    }

    /** @test */
    public function CounterCreation(): void
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
    public function TimerCreation(): void
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
    public function ProfilerReport(): void
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

        $this->assertEquals(0.00000214, $profiler->timer('new')->elapsed(), '', 0.00001);
        $profiler
            ->timer('new', 'vol', 'buy', 'tor')
            ->forceStart()->check();
        $report = $profiler->report();
        $report_extended = $profiler->report(true);
        $this->assertCount(2, $report);

        $this->assertCount(2, $report_extended);

    }
}