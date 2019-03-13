<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Formatters\ProfilerReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\SimpleCounterReportFormatter;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\SimpleCounterReport;
use AlecRabbit\Tools\Reports\TimerReport;
use AlecRabbit\Tools\SimpleCounter;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;
use PHPUnit\Framework\TestCase;

class ProfilerReportFormatterTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function wrongReport(): void
    {
        $formatter = new ProfilerReportFormatter();
        $timerReport = new TimerReport();
        $this->expectException(\RuntimeException::class);
        $formatter->process($timerReport);
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
        $str = $formatter->process($profilerReport);
        $this->assertContains(Strings::COUNTER, $str);
    }
}
