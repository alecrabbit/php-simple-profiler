<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\ExtendedCounter;
use AlecRabbit\Tools\Reports\ExtendedCounterReport;
use AlecRabbit\Tools\Reports\Formatters\ExtendedCounterReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\SimpleCounterReportFormatter;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\SimpleCounterReport;
use AlecRabbit\Tools\SimpleCounter;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;
use PHPUnit\Framework\TestCase;

class ExtendedCounterReportFormatterTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function wrongReport(): void
    {
        $formatter = new ExtendedCounterReportFormatter();
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
        $formatter = new ExtendedCounterReportFormatter();
        $timer = new ExtendedCounter();
        $counterReport = new ExtendedCounterReport();
        $counterReport->buildOn($timer);
        $str = $formatter->process($counterReport);
        $this->assertContains(Strings::COUNTER, $str);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function counterReportDefault(): void
    {
        $c = new ExtendedCounter();
        /** @var ExtendedCounterReport $report */
        $report = $c->report();
        $this->assertInstanceOf(ExtendedCounterReport::class, $report);
        $str = (string)$report;
        $this->assertNotContains(DEFAULT_NAME, $str);
        $this->assertContains(Strings::COUNTER, $str);
        $this->assertNotContains(Strings::VALUE, $str);
        $this->assertNotContains(Strings::STEP, $str);
        $this->assertNotContains(Strings::DIFF, $str);
        $this->assertNotContains(Strings::PATH, $str);
        $this->assertNotContains(Strings::LENGTH, $str);
        $this->assertNotContains(Strings::MIN, $str);
        $this->assertNotContains(Strings::MAX, $str);
        $this->assertNotContains(Strings::BUMPED, $str);
        $this->assertNotContains(Strings::FORWARD, $str);
        $this->assertNotContains(Strings::BACKWARD, $str);


        $this->assertEquals(DEFAULT_NAME, $report->getName());
        $this->assertEquals(0, $report->getValue());
        $this->assertEquals(1, $report->getStep());
        $this->assertEquals(0, $report->getInitialValue());
        $this->assertEquals(0, $report->getBumped());
    }
}
