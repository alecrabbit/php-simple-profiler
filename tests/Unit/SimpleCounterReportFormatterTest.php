<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Reports\Formatters\SimpleCounterReportFormatter;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\SimpleCounterReport;
use AlecRabbit\Tools\SimpleCounter;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;
use PHPUnit\Framework\TestCase;

class SimpleCounterReportFormatterTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function wrongReport(): void
    {
        $formatter = new SimpleCounterReportFormatter();
        $profilerReport = new ProfilerReport();
        $this->expectException(\RuntimeException::class);
        $formatter->process($profilerReport);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function correctReport(): void
    {
        $formatter = new SimpleCounterReportFormatter();
        $timer = new SimpleCounter();
        $timerReport = new SimpleCounterReport();
        $timerReport->buildOn($timer);
        $str = $formatter->process($timerReport);
        $this->assertStringContainsString(Strings::COUNTER, $str);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function counterReportDefault(): void
    {
        $c = new SimpleCounter();
        /** @var SimpleCounterReport $report */
        $report = $c->report();
        $this->assertInstanceOf(SimpleCounterReport::class, $report);
        $str = (string)$report;
        $this->assertStringNotContainsString(DEFAULT_NAME, $str);
        $this->assertStringContainsString(Strings::COUNTER, $str);
        $this->assertStringNotContainsString(Strings::VALUE, $str);
        $this->assertStringNotContainsString(Strings::STEP, $str);
        $this->assertStringNotContainsString(Strings::DIFF, $str);
        $this->assertStringNotContainsString(Strings::PATH, $str);
        $this->assertStringNotContainsString(Strings::LENGTH, $str);
        $this->assertStringNotContainsString(Strings::MIN, $str);
        $this->assertStringNotContainsString(Strings::MAX, $str);
        $this->assertStringNotContainsString(Strings::BUMPED, $str);
        $this->assertStringNotContainsString(Strings::FORWARD, $str);
        $this->assertStringNotContainsString(Strings::BACKWARD, $str);


        $this->assertEquals(DEFAULT_NAME, $report->getName());
        $this->assertEquals(0, $report->getValue());
        $this->assertEquals(1, $report->getStep());
        $this->assertEquals(0, $report->getInitialValue());
        $this->assertEquals(0, $report->getBumped());
    }
}
