<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\HRTimer;
use AlecRabbit\Tools\Reports\Formatters\TimerReportFormatter;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\TimerReport;
use AlecRabbit\Tools\Timer;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * @group time-sensitive
 */
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
        $this->assertStringNotContainsString(Strings::PROGRESS_BAR_MIN_WIDTH, $str);
        $this->assertStringNotContainsString(Strings::PROGRESS_BAR_MAX_WIDTH, $str);
        $this->assertStringNotContainsString(Strings::MARKS, $str);
        $this->assertStringMatchesFormat(
            '%f%ss',
            $t->elapsed()
        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerElapsed(): void
    {
        $t = new Timer('someName', false);
        $t->start();
        usleep(2000);
        $report = $t->report();
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringContainsString(Strings::ELAPSED, $str);
        $this->assertStringContainsString(Strings::TIMER, $str);
        $this->assertStringContainsString($t->getName(), $str);
        $this->assertStringMatchesFormat(
            '%f%ss',
            $t->elapsed()
        );
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
        $this->assertStringNotContainsString(Strings::AVERAGE, $str);
        $this->assertStringNotContainsString(Strings::LAST, $str);
        $this->assertStringNotContainsString(Strings::PROGRESS_BAR_MIN_WIDTH, $str);
        $this->assertStringNotContainsString(Strings::PROGRESS_BAR_MAX_WIDTH, $str);
        $this->assertStringNotContainsString(Strings::MARKS, $str);
        $this->assertStringMatchesFormat(
            '%f%ss',
            $t->elapsed()
        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerElapsedStartedManuallyAndChecked(): void
    {
        $t = new Timer('someName', false);
        $t->start();
        usleep(2000);
        $report = $t->report();
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringContainsString(Strings::ELAPSED, $str);
        $this->assertStringContainsString(Strings::TIMER, $str);
        $this->assertStringNotContainsString(Strings::AVERAGE, $str);
        $this->assertStringNotContainsString(Strings::LAST, $str);
        $this->assertStringNotContainsString(Strings::PROGRESS_BAR_MIN_WIDTH, $str);
        $this->assertStringNotContainsString(Strings::PROGRESS_BAR_MAX_WIDTH, $str);
        $this->assertStringNotContainsString(Strings::MARKS, $str);
        usleep(20);
        $t->check(1);
        $report = $t->report();
        $str = (string)$report;
        $this->assertStringContainsString(Strings::ELAPSED, $str);
        $this->assertStringContainsString(Strings::TIMER, $str);
        $this->assertStringContainsString(Strings::AVERAGE, $str);
        $this->assertStringContainsString(Strings::LAST, $str);
        $this->assertStringContainsString(Strings::PROGRESS_BAR_MIN_WIDTH, $str);
        $this->assertStringContainsString(Strings::PROGRESS_BAR_MAX_WIDTH, $str);
        $this->assertStringContainsString(Strings::MARKS, $str);
        $this->assertStringMatchesFormat(
            '%f%ss',
            $t->elapsed()
        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerElapsedNotStartedTwo(): void
    {
        $timer = new Timer(null, false);
        $elapsed = $timer->elapsed();
        $this->assertStringContainsString('.', $elapsed);
        $this->assertStringContainsString('s', $elapsed);
        $this->assertStringNotContainsString('seconds', $elapsed);
        $this->assertStringNotContainsString(Strings::TIMER, $elapsed);
        $this->assertStringNotContainsString($timer->getName(), $elapsed);
        $this->assertStringNotContainsString(Strings::AVERAGE, $elapsed);
        $this->assertStringNotContainsString(Strings::LAST, $elapsed);
        $this->assertStringNotContainsString(Strings::PROGRESS_BAR_MIN_WIDTH, $elapsed);
        $this->assertStringNotContainsString(Strings::PROGRESS_BAR_MAX_WIDTH, $elapsed);
        $this->assertStringNotContainsString(Strings::MARKS, $elapsed);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerValues(): void
    {
        $timer = new Timer();
        $timer->start();
        $count = 5;
        for ($i = 1; $i < $count; $i++) {
            sleep($i);
            $timer->check();
        }
        $this->assertEquals(2.5, $timer->getAverageValue());
        $this->assertEquals(1.0, $timer->getMinValue());
        $this->assertEquals(4.0, $timer->getMaxValue());
        $this->assertEquals(4.0, $timer->getLastValue());
        $this->assertEquals($count - 1, $timer->getCount());
        sleep(5);
        $timer->check();
        $this->assertEquals(5.0, $timer->getMaxValue());
        usleep(100000);
        $timer->check();
        $this->assertEqualsWithDelta(0.1, $timer->getMinValue(), 0.001);
        $report = $timer->report();
        $str = (string)$report;
        $avgStrValue = Pretty::time($timer->getAverageValue());
        $lastStrValue = Pretty::time($timer->getLastValue());
        $this->assertStringContainsString(Strings::ELAPSED, $str);
        $this->assertStringContainsString(Strings::TIMER, $str);
        $this->assertStringContainsString(Strings::AVERAGE . ': ' . $avgStrValue, $str);
        $this->assertStringContainsString(Strings::LAST . ': ' . $lastStrValue, $str);
        $this->assertStringContainsString(Strings::PROGRESS_BAR_MIN_WIDTH, $str);
        $this->assertStringContainsString(Strings::PROGRESS_BAR_MAX_WIDTH, $str);
        $this->assertStringContainsString(Strings::MARKS . ': ' . $timer->getCount(), $str);
    }
}
