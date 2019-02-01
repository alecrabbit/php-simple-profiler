<?php
/**
 * User: alec
 * Date: 27.12.18
 * Time: 17:48
 */
declare(strict_types=1);

namespace Tests\Unit;

use AlecRabbit\Tools\Contracts\StringsInterface;
use AlecRabbit\Tools\Reports\TimerReport;
use AlecRabbit\Tools\Timer;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class TimerReportTest extends TestCase
{
    /** @test */
    public function getReport(): void
    {
        $t = new Timer();
        /** @var TimerReport $report */
        $report = $t->getReport();
        $this->assertInstanceOf(TimerReport::class, $report);
        $this->assertEquals('default_name', $report->getName());
        $this->assertEquals(0.0, $report->getLastValue(), 'getLastValue');
        $this->assertEquals(0.0, $report->getAverageValue(), 'getAvgValue');
        $this->assertEquals(100000000.0, $report->getMinValue(), 'getMinValue');
        $this->assertEquals(0.0, $report->getMaxValue(), 'getMaxValue');
        $this->assertEquals(0, $report->getCount(), 'getCount');
        $this->assertEquals(0, $report->getMinValueIteration(), 'getMinValueIteration');
        $this->assertEquals(0, $report->getMaxValueIteration(), 'getMaxValueIteration');
        $this->assertEquals(0.0, $report->getElapsed(), 'getElapsed');
        $this->assertEquals($report->getCreation(), $report->getPrevious(), 'getCreation equals getPrevious');
        $this->assertEquals(true, $report->isStarted());
        $this->assertEquals(false, $report->isNotStarted());
        $this->assertEquals(true, $report->isStopped());
        $this->assertEquals(false, $report->isNotStopped());
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertContains(StringsInterface::ELAPSED, $str);
        $this->assertNotContains(StringsInterface::TIMER, $str);
        $this->assertNotContains(StringsInterface::AVERAGE, $str);
        $this->assertNotContains(StringsInterface::LAST, $str);
        $this->assertNotContains(StringsInterface::MIN, $str);
        $this->assertNotContains(StringsInterface::MAX, $str);
        $this->assertNotContains(StringsInterface::COUNT, $str);
    }


    /** @test */
    public function timerElapsed(): void
    {
        $t = new Timer('someName');
        $t->start();
        usleep(2000);
        $report = $t->getReport();
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertContains(StringsInterface::ELAPSED, $str);
        $this->assertContains(StringsInterface::TIMER, $str);
        $this->assertContains(StringsInterface::AVERAGE, $str);
        $this->assertContains(StringsInterface::LAST, $str);
        $this->assertContains(StringsInterface::MIN, $str);
        $this->assertContains(StringsInterface::MAX, $str);
        $this->assertContains(StringsInterface::COUNT, $str);
        $this->assertEquals('2.0ms', $t->elapsed());
        $this->assertStringMatchesFormat(
            '%fms',
            $t->elapsed()
        );
    }

    /** @test */
    public function timerElapsedNotStarted(): void
    {
        $t = new Timer('someName', false);
        usleep(2000);
        $report = $t->getReport();
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertContains(StringsInterface::ELAPSED, $str);
        $this->assertContains(StringsInterface::TIMER, $str);
        $this->assertContains(StringsInterface::AVERAGE, $str);
        $this->assertContains(StringsInterface::LAST, $str);
        $this->assertContains(StringsInterface::MIN, $str);
        $this->assertContains(StringsInterface::MAX, $str);
        $this->assertContains(StringsInterface::COUNT, $str);
        $this->assertEquals('2.0ms', $t->elapsed());
        $this->assertStringMatchesFormat(
            '%fms',
            $t->elapsed()
        );
    }


    /** @test */
    public function timerValuesStarted(): void
    {
        $t = new Timer();
        $count = 6;
        for ($i = 1; $i <= $count; $i++) {
            usleep(2000 + $i * 1000);
            $t->check($i);
        }
        usleep(1000);
        $t->check($i + 1);
        /** @var TimerReport $report */
        $report = $t->getReport();
        $this->assertEqualsWithDelta(0.001, $report->getLastValue(), 0.0001);
        $this->assertEqualsWithDelta(0.005, $report->getAverageValue(), 0.0005);
        $this->assertEqualsWithDelta(0.001, $report->getMinValue(), 0.0001);
        $this->assertEqualsWithDelta(0.008, $report->getMaxValue(), 0.0001);
        $this->assertEquals(8, $report->getMinValueIteration());
        $this->assertEquals(6, $report->getMaxValueIteration());
        $this->assertEquals(7, $report->getCount());
        $this->assertEqualsWithDelta(0.034, $report->getElapsed(), 0.0001);
    }

    /** @test */
    public function timerValuesNotStarted(): void
    {
        $t = new Timer(null, false);
        $count = 6;
        for ($i = 1; $i <= $count; $i++) {
            usleep(2000 + $i * 1000);
            $t->check($i);
        }
        usleep(1000);
        $t->check($i + 1);
        /** @var TimerReport $report */
        $report = $t->getReport();
        $this->assertEqualsWithDelta(0.001, $report->getLastValue(), 0.0001);
        $this->assertEqualsWithDelta(0.005, $report->getAverageValue(), 0.0005);
        $this->assertEqualsWithDelta(0.001, $report->getMinValue(), 0.0001);
        $this->assertEqualsWithDelta(0.008, $report->getMaxValue(), 0.0001);
        $this->assertEquals(8, $report->getMinValueIteration());
        $this->assertEquals(6, $report->getMaxValueIteration());
        $this->assertEquals(6, $report->getCount());
        $this->assertEqualsWithDelta(0.034, $report->getElapsed(), 0.0001);
    }

}