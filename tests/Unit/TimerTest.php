<?php

declare(strict_types=1);

namespace Tests\Unit;

use AlecRabbit\Tools\Timer;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class TimerTest extends TestCase
{

    /** @test */
    public function classCreation(): void
    {
        $timer = new Timer();
        $this->assertInstanceOf(Timer::class, $timer);
    }

    /** @test */
    public function timerCreationWithParameters(): void
    {
        $timer = new Timer('name');
        $this->assertEquals('name', $timer->getName());
        $timer = new Timer('name', false);
        $this->assertEquals('name', $timer->getName());
        $this->assertEquals(0.0, $timer->getLastValue(), 'getLastValue');
        $this->assertEquals(0.0, $timer->getAverageValue(), 'getAvgValue');
        $this->assertEquals(100000000.0, $timer->getMinValue(), 'getMinValue');
        $this->assertEquals(0.0, $timer->getMaxValue(), 'getMaxValue');
        $this->assertEquals(0, $timer->getCount(), 'getCount');
        $this->assertEquals(0, $timer->getMinValueIteration(), 'getMinValueIteration');
        $this->assertEquals(0, $timer->getMaxValueIteration(), 'getMaxValueIteration');
        $this->assertEquals(0.0, $timer->getElapsed(), 'getElapsed');
        $this->assertNotEquals($timer->getCreation(), $timer->getPrevious(), 'getCreation not equals getPrevious');
        $this->assertEquals(0.0, $timer->getPrevious(), 'getPrevious');
        $this->assertEquals(false, $timer->isStarted());
        $this->assertEquals(true, $timer->isNotStarted());
        $this->assertEquals(false, $timer->isStopped());
        $this->assertEquals(true, $timer->isNotStopped());
    }

    /** @test */
    public function timerDefaults(): void
    {
        $timer = new Timer();
        $this->assertEquals('default_name', $timer->getName());
        $this->assertEquals(0.0, $timer->getLastValue(), 'getLastValue');
        $this->assertEquals(0.0, $timer->getAverageValue(), 'getAvgValue');
        $this->assertEquals(100000000.0, $timer->getMinValue(), 'getMinValue');
        $this->assertEquals(0.0, $timer->getMaxValue(), 'getMaxValue');
        $this->assertEquals(0, $timer->getCount(), 'getCount');
        $this->assertEquals(0, $timer->getMinValueIteration(), 'getMinValueIteration');
        $this->assertEquals(0, $timer->getMaxValueIteration(), 'getMaxValueIteration');
        $this->assertEquals(0.0, $timer->getElapsed(), 'getElapsed');
        $this->assertEquals($timer->getCreation(), $timer->getPrevious(), 'getCreation equals getPrevious');
        $this->assertEquals(true, $timer->isStarted());
        $this->assertEquals(false, $timer->isNotStarted());
        $this->assertEquals(false, $timer->isStopped());
        $this->assertEquals(true, $timer->isNotStopped());
    }

    /** @test */
    public function timerAvgValue(): void
    {
        $timer = new Timer();
        $timer->start();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            sleep(1);
            $timer->check();
        }
        $this->assertEquals(1.0, $timer->getAverageValue());
        $this->assertEquals(1.0, $timer->getMinValue());
        $this->assertEquals(1.0, $timer->getMaxValue());
        $this->assertEquals(1.0, $timer->getLastValue());
        $this->assertEquals($count, $timer->getCount());
        $this->assertEquals(5.0, $timer->getElapsed());
    }

    /** @test */
    public function timerAvgValueBounds(): void
    {
        $timer = new Timer();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            $start = microtime(true);
            sleep(1);
            $stop = microtime(true);
            $timer->bounds($start, $stop);
        }
        $this->assertEquals(1.0, $timer->getAverageValue(), 'getAvgValue');
        $this->assertEquals(1.0, $timer->getMinValue(), 'getMinValue');
        $this->assertEquals(1.0, $timer->getMaxValue(), 'getMaxValue');
        $this->assertEquals(1.0, $timer->getLastValue(), 'getCurrentValue');
        $this->assertEquals($count, $timer->getCount());
    }
    /** @test */
    public function timerAvgValueBoundsNotStarted(): void
    {
        $timer = new Timer(null, false);
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            $start = microtime(true);
            sleep(1);
            $stop = microtime(true);
            $timer->bounds($start, $stop);
        }
        $this->assertEquals(1.0, $timer->getAverageValue(), 'getAvgValue');
        $this->assertEquals(1.0, $timer->getMinValue(), 'getMinValue');
        $this->assertEquals(1.0, $timer->getMaxValue(), 'getMaxValue');
        $this->assertEquals(1.0, $timer->getLastValue(), 'getCurrentValue');
        $this->assertEquals($count, $timer->getCount());
    }

    /** @test */
    public function timerElapsedNotStarted(): void
    {
        $timer = new Timer();
        $this->assertEquals('0.0ns', $timer->elapsed());
    }
}
