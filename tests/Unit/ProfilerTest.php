<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 21:28
 */

namespace Tests\Unit;

use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Timer;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class ProfilerTest extends TestCase
{

    /** @test */
    public function classCreation(): void
    {
        $profiler = new Profiler();
        $this->assertInstanceOf(Profiler::class, $profiler);
        $counters = $profiler->getCounters();
        foreach ($counters as $counter) {
            $this->assertInstanceOf(Counter::class, $counter);
        }
        $timers = $profiler->getTimers();
        foreach ($timers as $timer) {
            $this->assertInstanceOf(Timer::class, $timer);
        }
    }

    /** @test */
    public function counterCreation(): void
    {
        $profiler = new Profiler();
        $profiler->counter('new')->bump();
        $this->assertEquals(1, $profiler->counter('new')->getValue());
        $profiler->counter('new', 'vol', 'buy', 'tor')->bump();
        $this->assertStringMatchesFormat(
            '%s [%s, %s, %s]',
            $profiler
                ->counter('new', 'vol', 'buy', 'tor')
                ->getName()
        );
    }

    /** @test */
    public function timerCreation(): void
    {
        $profiler = new Profiler();
        $profiler->counter('new')->bump();
        $profiler->timer('new')->start();
        $profiler->timer('new')->check();
        $this->assertEquals('0.0ns', $profiler->timer('new')->elapsed());
        $this->assertStringMatchesFormat(
            '%s [%s, %s, %s]',
            $profiler
                ->timer('new', 'vol', 'buy', 'tor')
                ->getName()
        );
    }
}
