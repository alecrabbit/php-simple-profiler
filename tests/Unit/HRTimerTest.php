<?php

namespace Tests\Unit;

use AlecRabbit\Tools\HRTimer;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class HRTimerTest extends TestCase
{

    /** @test */
    public function classCreation(): void
    {
        $timer = new HRTimer();
        $this->assertInstanceOf(HRTimer::class, $timer);
    }

    /** @test */
    public function timerDefaultCreation(): void
    {
        $timer = new HRTimer();
        $this->assertEquals('default_name', $timer->getName());
        $timer = new HRTimer('name');
        $this->assertEquals('name', $timer->getName());
    }

    /** @test */
    public function timerAvgValue(): void
    {
        $timer = new HRTimer();
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
    public function timerAvgValueInterval(): void
    {
        $timer = new HRTimer();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            $start = microtime(true);
            sleep(1);
            $stop = microtime(true);
            $timer->interval($start, $stop);
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
        $timer = new HRTimer();
        $this->assertEquals('0.0ns', $timer->elapsed());
    }

//    /** @test */
//    public function timerReportsNotStarted(): void
//    {
//        $timer = new NewTimer();
//        $this->assertInstanceOf(TimerReport::class, $timer->getReport());
//    }
//
//    /** @test */
//    public function timerElapsed(): void
//    {
//        $timer = new Timer('someName');
//        $timer->start();
//        usleep(2000);
//        $this->assertIsString('' . $timer->getReport());
//        $this->assertEquals('2.0ms', $timer->elapsed());
//        $this->assertStringMatchesFormat(
//            '%fms',
//            $timer->elapsed(true)
//        );
//    }
//
//    /** @test */
//    public function timerValues(): void
//    {
//        $timer = new NewTimer();
//        $timer->start();
//        $count = 6;
//        for ($i = 1; $i <= $count; $i++) {
//            usleep(2000 + $i * 1000);
//            $timer->check($i);
//        }
//        usleep(1000);
//        $timer->check($i + 1);
//        /** @var TimerReport $report */
//        $report = $timer->getReport();
//        $this->assertEqualsWithDelta(0.001, $report->getLastValue(), 0.0001);
//        $this->assertEqualsWithDelta(0.005, $report->getAverageValue(), 0.0005);
//        $this->assertEqualsWithDelta(0.001, $report->getMinValue(), 0.0001);
//        $this->assertEqualsWithDelta(0.008, $report->getMaxValue(), 0.0001);
//        $this->assertEquals(8, $report->getMinValueIteration());
//        $this->assertEquals(6, $report->getMaxValueIteration());
//        $this->assertEquals(7, $report->getCount());
//    }
}