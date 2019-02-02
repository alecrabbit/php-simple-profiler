<?php

declare(strict_types=1);

namespace Tests\Unit;

use AlecRabbit\Tools\Contracts\StringsInterface;
use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Reports\CounterReport;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;
use PHPUnit\Framework\TestCase;

class CounterReportTest extends TestCase
{
    /** @test */
    public function counterReport(): void
    {
        $c = new Counter();
        /** @var CounterReport $report */
        $report = $c->getReport();
        $this->assertInstanceOf(CounterReport::class, $report);
        $str = (string)$report;
        $this->assertNotContains(DEFAULT_NAME, $str);
        $this->assertContains(StringsInterface::COUNTER, $str);
        $this->assertNotContains(StringsInterface::VALUE, $str);
        $this->assertNotContains(StringsInterface::STEP, $str);


        $this->assertEquals(DEFAULT_NAME, $report->getName());
        $this->assertEquals(0, $report->getValue());
        $this->assertEquals(1, $report->getStep());

        $name = 'name';
        $c = new Counter($name);
        /** @var CounterReport $report */
        $report = $c->getReport();
        $this->assertInstanceOf(CounterReport::class, $report);
        $str = (string)$report;
        $this->assertContains($name, $str);
        $this->assertContains(StringsInterface::COUNTER, $str);
        $this->assertContains(StringsInterface::VALUE, $str);
        $this->assertContains(StringsInterface::STEP, $str);
        $this->assertEquals($name, $report->getName());
        $this->assertEquals(0, $report->getValue());
        $this->assertEquals(1, $report->getStep());
    }

}
