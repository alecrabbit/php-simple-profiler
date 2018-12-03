<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 21:28
 */

namespace Unit;

use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Reports\CounterReport;
use PHPUnit\Framework\TestCase;

class CounterTest extends TestCase
{
    /** @test */
    public function classCreation(): void
    {
        $counter = new Counter();
        $this->assertInstanceOf(Counter::class, $counter);
    }

    /** @test */
    public function counterDefaultCreation(): void
    {
        $counter = new Counter();
        $this->assertEquals('default_name', $counter->getName());
        $counter = new Counter('name');
        $this->assertEquals('name', $counter->getName());
    }

    /** @test */
    public function counterBump(): void
    {
        $counter = new Counter();
        $counter->bumpUp();
        $this->assertEquals(1, $counter->getValue());
        $counter->bumpWith(2);
        $this->assertEquals(3, $counter->getValue());
        $counter->bumpWith(2, true);
        $this->assertEquals(5, $counter->getValue());
        $counter->bumpUp();
        $this->assertEquals(7, $counter->getValue());
        $counter->bumpDown();
        $this->assertEquals(5, $counter->getValue());
    }

    /** @test */
    public function counterWithSetStep(): void
    {
        $counter = (new Counter())->setStep(2);
        $counter->bump();
        $this->assertEquals(2, $counter->getValue());
        $counter->bump();
        $this->assertEquals(4, $counter->getValue());
        $counter = (new Counter(null, 10))->setStep(-1);
        $counter->bump();
        $this->assertEquals(9, $counter->getValue());
        $counter->bump();
        $this->assertEquals(8, $counter->getValue());
        $counter = (new Counter())->setStep(-1);
        $counter->bump();
        $this->assertEquals(-1, $counter->getValue());
        $counter->bump();
        $this->assertEquals(-2, $counter->getValue());
    }

    /** @test */
    public function counterWithException(): void
    {
        $this->expectException(\RuntimeException::class);
        (new Counter())->setStep(0);
    }

    /** @test */
    public function counterWithExceptionTwo(): void
    {
        $counter = new Counter();
        $this->expectException(\RuntimeException::class);
        $counter->bumpWith(0, true);
    }

    /** @test */
    public function counterWithExceptionThree(): void
    {
        $counter = new Counter();
        $this->expectException(\RuntimeException::class);
        $counter->bumpWith(0);
    }

    /** @test */
    public function counterReport(): void
    {
        $counter = new Counter();
        /** @var CounterReport $report */
        $report = $counter->report();
        $this->assertInstanceOf(CounterReport::class, $report);
        $this->assertEquals(0, $report->getValue());
        $this->assertEquals(1, $report->getStep());
    }
}