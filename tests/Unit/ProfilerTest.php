<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 21:28
 */

namespace Unit;

use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Timer;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * @group time-sensitive
 */
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
        $this->assertEquals('0ms', $profiler->timer('new')->elapsed(), '', 0.001);
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
        $profiler->timer('new')->check();
        sleep(1);
        $profiler->timer('new')->check();
        $profiler->timer()->check();
        sleep(1);
        $profiler->timer()->check();
        $this->assertEquals(1, $profiler->counter('new')->getValue());

        $this->assertInternalType('string', $profiler->timer('new')->elapsed());
        $this->assertEquals('2000ms', $profiler->timer()->elapsed());
        $this->assertEquals('2000ms', $profiler->timer('new')->elapsed());
        $profiler->timer('new', 'vol', 'buy', 'tor');
        $report = $profiler->report();
        $this->assertInstanceOf(ProfilerReport::class, $report);

        $counters = $profiler->getCounters();
        foreach ($counters as $counter) {
            $this->assertInstanceOf(Counter::class, $counter);
        }
    }
}