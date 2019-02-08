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
    protected const NAME = 'name';

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
        $this->assertNotContains(StringsInterface::DIFF, $str);
        $this->assertNotContains(StringsInterface::PATH, $str);
        $this->assertNotContains(StringsInterface::LENGTH, $str);
        $this->assertNotContains(StringsInterface::MIN, $str);
        $this->assertNotContains(StringsInterface::MAX, $str);
        $this->assertNotContains(StringsInterface::BUMPED, $str);
        $this->assertNotContains(StringsInterface::FORWARD, $str);
        $this->assertNotContains(StringsInterface::BACKWARD, $str);


        $this->assertEquals(DEFAULT_NAME, $report->getName());
        $this->assertEquals(0, $report->getValue());
        $this->assertEquals(1, $report->getStep());
        $this->assertEquals(0, $report->getDiff());
        $this->assertEquals(0, $report->getMin());
        $this->assertEquals(0, $report->getMax());
        $this->assertEquals(0, $report->getInitialValue());
        $this->assertEquals(0, $report->getBumpedForward());
        $this->assertEquals(0, $report->getBumpedBack());

        $c = new Counter(self::NAME);
        $report = $c->getReport();
        $this->assertInstanceOf(CounterReport::class, $report);
        $str = (string)$report;
        $this->assertContains(self::NAME, $str);
        $this->assertContains(StringsInterface::COUNTER, $str);
        $this->assertContains(StringsInterface::VALUE, $str);
        $this->assertContains(StringsInterface::STEP, $str);
        $this->assertEquals(self::NAME, $report->getName());
        $this->assertEquals(0, $report->getValue());
        $this->assertEquals(1, $report->getStep());
        $this->assertEquals(0, $report->getDiff());
        $this->assertEquals(0, $report->getMin());
        $this->assertEquals(0, $report->getMax());
        $this->assertEquals(0, $report->getInitialValue());
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
        $this->assertNotContains(StringsInterface::BUMPED, $str);
        $this->assertNotContains(StringsInterface::FORWARD, $str);
        $this->assertNotContains(StringsInterface::BACKWARD, $str);


        $this->assertEquals(DEFAULT_NAME, $report->getName());
        $this->assertEquals(1, $report->getValue());
        $this->assertEquals(1, $report->getStep());
        $this->assertEquals(1, $report->getDiff());
        $this->assertEquals(3, $report->getPath());
        $this->assertEquals(0, $report->getMin());
        $this->assertEquals(2, $report->getMax());
        $this->assertEquals(0, $report->getInitialValue());
        $this->assertEquals(3, $report->getLength());
        $this->assertEquals(2, $report->getBumpedForward());
        $this->assertEquals(1, $report->getBumpedBack());


        $c = new Counter(self::NAME);
        $c->setInitialValue(10);
        $c->bump();
        $c->bump();
        $c->bumpBack();
        /** @var CounterReport $report */
        $report = $c->getReport();
        $this->assertInstanceOf(CounterReport::class, $report);

        $str = (string)$report;
        $this->assertContains(self::NAME, $str);
        $this->assertContains(StringsInterface::COUNTER, $str);
        $this->assertContains(StringsInterface::VALUE, $str);
        $this->assertContains(StringsInterface::STEP, $str);
        $this->assertContains(StringsInterface::DIFF, $str);
        $this->assertContains(StringsInterface::PATH, $str);
        $this->assertContains(StringsInterface::LENGTH, $str);
        $this->assertContains(StringsInterface::MAX, $str);
        $this->assertContains(StringsInterface::MIN, $str);
        $this->assertContains(StringsInterface::BUMPED, $str);
        $this->assertContains(StringsInterface::FORWARD, $str);
        $this->assertContains(StringsInterface::BACKWARD, $str);

        $this->assertEquals(self::NAME, $report->getName());
        $this->assertEquals(11, $report->getValue());
        $this->assertEquals(1, $report->getStep());
        $this->assertEquals(1, $report->getDiff());
        $this->assertEquals(3, $report->getPath());
        $this->assertEquals(10, $report->getMin());
        $this->assertEquals(12, $report->getMax());
        $this->assertEquals(10, $report->getInitialValue());
        $this->assertEquals(13, $report->getLength());
        $this->assertEquals(2, $report->getBumpedForward());
        $this->assertEquals(1, $report->getBumpedBack());
    }
}
