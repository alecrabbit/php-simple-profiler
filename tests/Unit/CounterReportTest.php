<?php

declare(strict_types=1);

namespace Tests\Unit;

use AlecRabbit\Tools\Contracts\StringsInterface;
use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Reports\CounterReport;
use PHPUnit\Framework\TestCase;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class CounterReportTest extends TestCase
{
    /** @test */
    public function counterReportDefault(): void
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
        $this->assertEquals(0, $report->getDiff());
        $this->assertEquals(0, $report->getBumpedForward());
        $this->assertEquals(0, $report->getBumpedBack());

        $name = 'name';
        $c = new Counter($name);
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
        $this->assertEquals(0, $report->getDiff());
        $this->assertEquals(0, $report->getBumpedForward());
        $this->assertEquals(0, $report->getBumpedBack());
    }

    /** @test */
    public function counterReport(): void
    {
        $c = new Counter();
        $c->bump();
        $c->bump();
        $c->bump(1, false);
        /** @var CounterReport $report */
        $report = $c->getReport();
        $this->assertInstanceOf(CounterReport::class, $report);

        $str = (string)$report;
        $this->assertNotContains(DEFAULT_NAME, $str);
        $this->assertContains(StringsInterface::COUNTER, $str);
        $this->assertNotContains(StringsInterface::VALUE, $str);
        $this->assertNotContains(StringsInterface::STEP, $str);
        $this->assertNotContains(StringsInterface::DIFF, $str);
        $this->assertNotContains(StringsInterface::PATH, $str);
        $this->assertNotContains(StringsInterface::LENGTH, $str);


        $this->assertEquals(DEFAULT_NAME, $report->getName());
        $this->assertEquals(1, $report->getValue());
        $this->assertEquals(1, $report->getStep());
        $this->assertEquals(1, $report->getDiff());
        $this->assertEquals(3, $report->getPath());
        $this->assertEquals(3, $report->getLength());
        $this->assertEquals(2, $report->getBumpedForward());
        $this->assertEquals(1, $report->getBumpedBack());


        $name = 'name';
        $c = new Counter($name);
        $c->setInitialValue(10);
        $c->bump();
        $c->bump();
        $c->bumpBack();
        /** @var CounterReport $report */
        $report = $c->getReport();
        $this->assertInstanceOf(CounterReport::class, $report);

        $str = (string)$report;
        $this->assertContains($name, $str);
        $this->assertContains(StringsInterface::COUNTER, $str);
        $this->assertContains(StringsInterface::VALUE, $str);
        $this->assertContains(StringsInterface::STEP, $str);
        $this->assertContains(StringsInterface::DIFF, $str);
        $this->assertContains(StringsInterface::PATH, $str);
        $this->assertContains(StringsInterface::LENGTH, $str);

        $this->assertEquals($name, $report->getName());
        $this->assertEquals(11, $report->getValue());
        $this->assertEquals(1, $report->getStep());
        $this->assertEquals(1, $report->getDiff());
        $this->assertEquals(3, $report->getPath());
        $this->assertEquals(13, $report->getLength());
        $this->assertEquals(2, $report->getBumpedForward());
        $this->assertEquals(1, $report->getBumpedBack());
    }
}
