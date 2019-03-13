<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\HRTimer;
use AlecRabbit\Tools\Reports\Formatters\TimerReportFormatter;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\TimerReport;
use AlecRabbit\Tools\Timer;
use PHPUnit\Framework\TestCase;

class TimerReportFormatterTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function wrongReport(): void
    {
        $formatter = new TimerReportFormatter();
        $profilerReport = new ProfilerReport();
        $this->expectException(\RuntimeException::class);
        $formatter->process($profilerReport);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function correctReportTimer(): void
    {
        $formatter = new TimerReportFormatter();
        $timer = new Timer();
        $timerReport = new TimerReport();
        $timerReport->buildOn($timer);
        $str = $formatter->process($timerReport);
        $this->assertContains(Strings::ELAPSED, $str);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function correctReportHRTimer(): void
    {
        $formatter = new TimerReportFormatter();
        $timer = new HRTimer();
        $timerReport = new TimerReport();
        $timerReport->buildOn($timer);
        $str = $formatter->process($timerReport);
        $this->assertContains(Strings::ELAPSED, $str);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function getReport(): void
    {
        $t = new Timer();
        /** @var TimerReport $report */
        $report = $t->report();
        $this->assertInstanceOf(TimerReport::class, $report);
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertContains(Strings::ELAPSED, $str);
        $this->assertNotContains(Strings::TIMER, $str);
        $this->assertNotContains(Strings::AVERAGE, $str);
        $this->assertNotContains(Strings::LAST, $str);
        $this->assertNotContains(Strings::MIN, $str);
        $this->assertNotContains(Strings::MAX, $str);
        $this->assertNotContains(Strings::COUNT, $str);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerElapsed(): void
    {
        $t = new Timer('someName');
        $t->start();
        usleep(2000);
        $report = $t->report();
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertContains(Strings::ELAPSED, $str);
        dump($str);
//        $this->assertEquals('2.0ms', $t->elapsed());
//        $this->assertStringMatchesFormat(
//            '%fms',
//            $t->elapsed()
//        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerElapsedNotStarted(): void
    {
        $t = new Timer('someName', false);
        usleep(2000);
        $report = $t->report();
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertContains(Strings::ELAPSED, $str);
        $this->assertContains(Strings::TIMER, $str);
        $this->assertContains(Strings::AVERAGE, $str);
        $this->assertContains(Strings::LAST, $str);
        $this->assertContains(Strings::MIN, $str);
        $this->assertContains(Strings::MAX, $str);
        $this->assertContains(Strings::COUNT, $str);
//        $this->assertEquals('2.0ms', $t->elapsed());
//        $this->assertStringMatchesFormat(
//            '%fms',
//            $t->elapsed()
//        );
    }
}
