<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 21:28
 */

namespace Unit;


use AlecRabbit\Profiler\Counter;
use PHPUnit\Framework\TestCase;

class CounterTest extends TestCase
{
    /** @test */
    public function ClassCreation(): void
    {
        $counter = new Counter();
        $this->assertInstanceOf(Counter::class, $counter);
    }

    /** @test */
    public function CounterDefaultCreation(): void
    {
        $counter = new Counter();
        $this->assertEquals('default', $counter->getName());
        $counter = new Counter('name');
        $this->assertEquals('name', $counter->getName());
    }

    /** @test */
    public function CounterBump(): void
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
    public function CounterWithSetStep(): void
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
    public function CounterWithException(): void
    {
        $this->expectException(\RuntimeException::class);
        (new Counter())->setStep(0);
    }

    /** @test */
    public function CounterWithExceptionTwo(): void
    {
        $counter = new Counter();
        $this->expectException(\RuntimeException::class);
        $counter->bumpWith(0, true);
    }

    /** @test */
    public function CounterWithExceptionThree(): void
    {
        $counter = new Counter();
        $this->expectException(\RuntimeException::class);
        $counter->bumpWith(0);
    }

    /** @test */
    public function CounterReport(): void
    {
        $counter = new Counter();
        $report = $counter->report();
        $this->assertCount(3, $report);
    }
}