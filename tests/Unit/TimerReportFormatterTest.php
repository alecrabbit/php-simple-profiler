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
        $this->assertStringContainsString(Strings::ELAPSED, $str);
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
        $this->assertStringContainsString(Strings::ELAPSED, $str);
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
        $this->assertStringContainsString(Strings::ELAPSED, $str);
        $this->assertStringNotContainsString(Strings::TIMER, $str);
        $this->assertStringNotContainsString(Strings::AVERAGE, $str);
        $this->assertStringNotContainsString(Strings::LAST, $str);
        $this->assertStringNotContainsString(Strings::MIN, $str);
        $this->assertStringNotContainsString(Strings::MAX, $str);
        $this->assertStringNotContainsString(Strings::COUNT, $str);
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
        $this->assertStringContainsString(Strings::ELAPSED, $str);
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
        $this->assertStringContainsString(Strings::ELAPSED, $str);
        $this->assertStringContainsString(Strings::TIMER, $str);
        $this->assertStringContainsString(Strings::AVERAGE, $str);
        $this->assertStringContainsString(Strings::LAST, $str);
        $this->assertStringContainsString(Strings::MIN, $str);
        $this->assertStringContainsString(Strings::MAX, $str);
        $this->assertStringContainsString(Strings::COUNT, $str);
//        $this->assertEquals('2.0ms', $t->elapsed());
//        $this->assertStringMatchesFormat(
//            '%fms',
//            $t->elapsed()
//        );
    }
}
