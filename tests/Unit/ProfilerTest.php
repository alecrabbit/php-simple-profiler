<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 21:28
 */

namespace Unit;

use AlecRabbit\Profiler\Counter;
use AlecRabbit\Profiler\Profiler;
use AlecRabbit\Profiler\Timer;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

class ProfilerTest extends TestCase
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
    }

    /** @test */
    public function counterCreation(): void
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
    public function timerCreation(): void
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
    public function profilerReport(): void
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

        $this->assertInternalType('float', $profiler->timer('new')->elapsed());
        $profiler
            ->timer('new', 'vol', 'buy', 'tor')
            ->forceStart()
            ->check();
        $report = $profiler->report();
        $report_extended = $profiler->report(true);
        $this->assertCount(2, $report);

        $this->assertCount(2, $report_extended);

        $counters = $profiler->getCounters();
        foreach ($counters as $counter) {
            $this->assertInstanceOf(Counter::class, $counter);
        }
    }
}