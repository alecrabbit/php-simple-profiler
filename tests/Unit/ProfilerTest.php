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
        $profiler->timer('new')->start();
        $profiler->timer('new')->check();
        $this->assertEquals(0.00000214, $profiler->timer('new')->elapsed(),'', 0.001);
        $this->assertStringMatchesFormat(
            '%s [%s, %s, %s]',
            $profiler
                ->timer('new', 'vol', 'buy', 'tor')
                ->getName()
        );
    }
}