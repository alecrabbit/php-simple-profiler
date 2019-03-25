<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Formatters\SimpleCounterReportFormatter;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\SimpleCounterReport;
use AlecRabbit\Tools\SimpleCounter;
use PHPUnit\Framework\TestCase;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

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

    /**
     * @test
     * @throws \Exception
     */
    public function counterReportDefaultWithName(): void
    {
        $name = 'name';
        $c = new SimpleCounter($name);
        /** @var SimpleCounterReport $report */
        $report = $c->report();
        $this->assertInstanceOf(SimpleCounterReport::class, $report);
        $str = (string)$report;
        $this->assertStringContainsString($name, $str);
        $this->assertStringContainsString(Strings::COUNTER, $str);
        $this->assertStringContainsString(Strings::VALUE, $str);
        $this->assertStringContainsString(Strings::STEP, $str);
        $this->assertStringNotContainsString(Strings::DIFF, $str);
        $this->assertStringNotContainsString(Strings::PATH, $str);
        $this->assertStringNotContainsString(Strings::LENGTH, $str);
        $this->assertStringNotContainsString(Strings::MIN, $str);
        $this->assertStringNotContainsString(Strings::MAX, $str);
        $this->assertStringContainsString(Strings::BUMPED, $str);
        $this->assertStringContainsString(Strings::FORWARD, $str);
        $this->assertStringNotContainsString(Strings::BACKWARD, $str);

        $this->assertEquals($name, $report->getName());
        $this->assertEquals(0, $report->getValue());
        $this->assertEquals(1, $report->getStep());
        $this->assertEquals(0, $report->getInitialValue());
        $this->assertEquals(0, $report->getBumped());
    }
}
