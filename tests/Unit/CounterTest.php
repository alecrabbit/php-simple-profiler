<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 21:28
 */

namespace Tests\Unit;

use AlecRabbit\Tools\Counter;
use PHPUnit\Framework\TestCase;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class CounterTest extends TestCase
{
    /** @test */
    public function counterDefaultCreation(): void
    {
        $c = new Counter();
        $this->assertInstanceOf(Counter::class, $c);
        $this->assertEquals(DEFAULT_NAME, $c->getName());
        $c = new Counter('name');
        $this->assertEquals('name', $c->getName());
    }

    /** @test */
    public function counterCreationWithParams(): void
    {
        $c = new Counter('name', 1, 1);
        $this->assertEquals('name', $c->getName());
        $this->assertEquals(1, $c->getValue());
        $c = new Counter(null, 2, 2);
        $this->assertEquals(DEFAULT_NAME, $c->getName());
        $this->assertEquals(2, $c->getValue());
        $c->bump();
        $this->assertEquals(4, $c->getValue());
    }

    /** @test */
    public function counterBump(): void
    {
        $c = new Counter();
        $this->assertEquals(DEFAULT_NAME, $c->getName());

        $c->bump();
        $this->assertEquals(1, $c->getValue());
        $c->setStep(2)->bump();
        $this->assertEquals(3, $c->getValue());
        $c->bump();
        $this->assertEquals(5, $c->getValue());
        $c->bump();
        $this->assertEquals(7, $c->getValue());
        $c->bumpReverse();
        $this->assertEquals(5, $c->getValue());
    }

    /** @test */
    public function counterWithSetStep(): void
    {
        $c = (new Counter())->setStep(2);
        $this->assertEquals(DEFAULT_NAME, $c->getName());

        $c->bump();
        $this->assertEquals(2, $c->getValue());
        $c->bump();
        $this->assertEquals(4, $c->getValue());

        $c = (new Counter(null, 1, 10))->setStep(-1);
        $c->bump();
        $this->assertEquals(9, $c->getValue());
        $c->bump();
        $this->assertEquals(8, $c->getValue());
        $c = (new Counter())->setStep(-1);
        $c->bump();
        $this->assertEquals(-1, $c->getValue());
        $c->bump();
        $this->assertEquals(-2, $c->getValue());
        $this->expectException(\RuntimeException::class);
        $c->setStep(0);
    }

    /** @test */
    public function counterWithSetStartValue(): void
    {
        $c = (new Counter())
            ->setStep(2)
            ->setInitialValue(2);
        $this->assertEquals(DEFAULT_NAME, $c->getName());
        $c->bump();
        $this->assertEquals(4, $c->getValue());
        $c->bump();
        $this->assertEquals(6, $c->getValue());
        $c = (new Counter())
            ->setInitialValue(2);
        $c->bump();
        $this->assertEquals(3, $c->getValue());
        $c->bump();
        $this->assertEquals(4, $c->getValue());
        $this->expectException(\RuntimeException::class);
        $c->setInitialValue(10);
    }

// *******


//    /** @test */
//    public function counterWithException(): void
//    {
//        $this->expectException(\RuntimeException::class);
//        (new Counter())->setStep(0);
//    }
//
//    /** @test */
//    public function counterWithExceptionTwo(): void
//    {
//        $counter = new Counter();
//        $this->expectException(\RuntimeException::class);
//        $counter->bumpWith(0, true);
//    }
//
//    /** @test */
//    public function counterWithExceptionThree(): void
//    {
//        $counter = new Counter();
//        $this->expectException(\RuntimeException::class);
//        $counter->bumpWith(0);
//    }
//
//    /** @test */
//    public function counterReport(): void
//    {
//        $name = 'name';
//        $counter = new Counter($name);
//        /** @var CounterReport $report */
//        $report = $counter->getReport();
//        $string = (string)$report;
//        $this->assertContains($name, $string);
//        $this->assertInstanceOf(CounterReport::class, $report);
//        $this->assertEquals(0, $report->getValue());
//        $this->assertEquals(1, $report->getStep());
//    }
//
//    /** @test */
//    public function counterReportDefault(): void
//    {
//        $counter = new Counter();
//        /** @var CounterReport $report */
//        $report = $counter->getReport();
//        $this->assertInstanceOf(CounterReport::class, $report);
//        $this->assertEquals(0, $report->getValue());
//        $this->assertEquals(1, $report->getStep());
//    }
}