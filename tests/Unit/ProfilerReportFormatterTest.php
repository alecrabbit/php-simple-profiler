<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Formatters\ProfilerReportFormatter;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\TimerReport;
use PHPUnit\Framework\TestCase;

class ProfilerReportFormatterTest extends TestCase
{
    public const NAME = 'name';

    /**
     * @test
     * @throws \Exception
     */
    public function wrongReport(): void
    {
        $formatter = new ProfilerReportFormatter();
        $timerReport = new TimerReport();
        $this->expectException(\RuntimeException::class);
        $formatter->format($timerReport);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function correctReport(): void
    {
        $formatter = new ProfilerReportFormatter();
        $profiler = new Profiler();
        $profilerReport = new ProfilerReport();
        $profilerReport->buildOn($profiler);
        $str = $formatter->format($profilerReport);
        $this->assertStringNotContainsString(Strings::COUNTER, $str);
        $this->assertStringContainsString(Strings::ELAPSED, $str);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function profilerReport(): void
    {
        $profiler = new Profiler();
        $profiler->counter(self::NAME)->bump();
        $profiler->counter()->bump();

        $profiler->timer(self::NAME)->check();
        $profiler->timer(self::NAME)->check();
        $profiler->timer()->check();
        $profiler->timer()->check();

        $report = $profiler->report();
        $this->assertInstanceOf(ProfilerReport::class, $report);

        $this->assertEquals(1, $profiler->counter(self::NAME)->getValue());
        $this->assertEquals(1, $profiler->counter()->getValue());

        $this->assertIsString($profiler->timer(self::NAME)->elapsed());
        $str = (string)$report;
        $this->assertStringContainsString(self::NAME, $str);
        $this->assertStringContainsString(Strings::COUNTER, $str);
        $this->assertStringContainsString(Strings::TIMER, $str);
        $this->assertStringContainsString(Strings::ELAPSED, $str);
    }
}
