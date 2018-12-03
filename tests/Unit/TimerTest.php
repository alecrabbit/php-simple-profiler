<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 23:03
 */

namespace Unit;


use AlecRabbit\Tools\Reports\TimerReport;
use AlecRabbit\Tools\Timer;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Class TimerTest
 * @group time-sensitive
 */
class TimerTest extends TestCase
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
        $timer = new Timer();
        $this->assertInstanceOf(Timer::class, $timer);
    }

    /** @test */
    public function timerDefaultCreation(): void
    {
        $timer = new Timer();
        $this->assertEquals('default_name', $timer->getName());
        $timer = new Timer('name');
        $this->assertEquals('name', $timer->getName());
    }

    /**
     * @test
     */
    public function timerAvgValue(): void
    {
        $timer = new Timer();
        $timer->start();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            sleep(1);
            $timer->check();
        }
        $this->assertEquals(1.0, $timer->getAverageValue(), 'getAvgValue');
        $this->assertEquals(1.0, $timer->getMinValue(), 'getMinValue');
        $this->assertEquals(1.0, $timer->getMaxValue(), 'getMaxValue');
        $this->assertEquals(1.0, $timer->getLastValue(), 'getCurrentValue');
        $this->assertEquals($count, $timer->getCount());
    }

    /** @test */
    public function timerElapsedNotStarted(): void
    {
        $timer = new Timer();
//        $this->expectException(\RuntimeException::class);
        $this->assertEquals('0ms', $timer->elapsed());
    }

    /** @test */
    public function timerReportsNotStarted(): void
    {
        $timer = new Timer();
        $this->assertInstanceOf(TimerReport::class, $timer->report());
    }

    /** @test */
    public function timerElapsed(): void
    {
        $timer = new Timer();
        $timer->start();
        usleep(1000);
        $this->assertEquals('1ms', $timer->elapsed(), 'Elapsed time', 0.0002);
        $this->assertStringMatchesFormat(
            '%fms',
            $timer->elapsed(true)
        );
    }

    /** @test */
    public function timerValues(): void
    {
        $timer = new Timer();
        $timer->start();
        $count = 6;
        for ($i = 1; $i <= $count; $i++) {
            usleep(2000 + $i * 1000);
            $timer->check($i);
        }
        usleep(1000);
        $timer->check($i + 1);
        /** @var TimerReport $report */
        $report = $timer->report();
        $this->assertEquals(0.001, $report->getLastValue(), '', 0.0001);
        $this->assertEquals(0.005, $report->getAverageValue(), '', 0.0005);
        $this->assertEquals(0.001, $report->getMinValue(), '', 0.0001);
        $this->assertEquals(8, $report->getMinValueIteration());
        $this->assertEquals(0.008, $report->getMaxValue(), '', 0.0001);
        $this->assertEquals(6, $report->getMaxValueIteration());
        $this->assertEquals(7, $report->getCount());
    }
}