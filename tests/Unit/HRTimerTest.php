<?php

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\HRTimer;
use PHPUnit\Framework\TestCase;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

/**
 * @group time-sensitive
 */
class HRTimerTest extends TestCase
{
    /** @var bool */
    private $below73 = false;

    /**
     * @test
     * @throws \Exception
     */
    public function instance(): void
    {
        if ($this->below73) {
            $this->expectException(\RuntimeException::class);
        }
        $timer = new HRTimer();
        $this->assertInstanceOf(HRTimer::class, $timer);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerAvgValueBounds(): void
    {
        if ($this->below73) {
            $this->expectException(\RuntimeException::class);
        }
        $timer = new HRTimer();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            $start = hrtime(true);
            $stop = $start + 1000000000;
            $timer->bounds($start, $stop);
        }
        $this->assertEquals(1.0, $timer->getAverageValue(), 'getAvgValue');
        $this->assertEquals(1.0, $timer->getMinValue(), 'getMinValue');
        $this->assertEquals(1.0, $timer->getMaxValue(), 'getMaxValue');
        $this->assertEquals(1.0, $timer->getLastValue(), 'getCurrentValue');
        $this->assertEquals($count, $timer->getCount());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerAvgValueBoundsUSleep(): void
    {
        if ($this->below73) {
            $this->expectException(\RuntimeException::class);
        }
        $timer = new HRTimer();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            $start = hrtime(true);
            $stop = $start + 10000000;
            $timer->bounds($start, $stop);
        }
        $this->assertEqualsWithDelta(0.01, $timer->getAverageValue(), 0.001);
        $this->assertEqualsWithDelta(0.01, $timer->getMinValue(), 0.001);
        $this->assertEqualsWithDelta(0.01, $timer->getMaxValue(), 0.001);
        $this->assertEqualsWithDelta(0.01, $timer->getLastValue(), 0.001);
        $this->assertEquals($count, $timer->getCount());
        $start = hrtime(true);
        $stop = $start + 50000000;
        $timer->bounds($start, $stop);
        $this->assertEqualsWithDelta(0.05, $timer->getMaxValue(), 0.001);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function instanceParams(): void
    {
        if ($this->below73) {
            $this->expectException(\RuntimeException::class);
        }
        $timer = new HRTimer();
        $this->assertEquals(DEFAULT_NAME, $timer->getName());
        $this->assertEquals(true, $timer->isStarted());
        $this->assertEquals(false, $timer->isNotStarted());
        $this->assertEquals(false, $timer->isStopped());
        $this->assertEquals(true, $timer->isNotStopped());
        $timer = new HRTimer(null);
        $this->assertEquals(DEFAULT_NAME, $timer->getName());
        $this->assertEquals(true, $timer->isStarted());
        $this->assertEquals(false, $timer->isNotStarted());
        $this->assertEquals(false, $timer->isStopped());
        $this->assertEquals(true, $timer->isNotStopped());

        $name = 'name';
        $timer = new HRTimer($name, false);
        $this->assertEquals($name, $timer->getName());
        $this->assertEquals(false, $timer->isStarted());
        $this->assertEquals(true, $timer->isNotStarted());
        $this->assertEquals(false, $timer->isStopped());
        $this->assertEquals(true, $timer->isNotStopped());
        $timer = new HRTimer(null, false);
        $this->assertEquals(DEFAULT_NAME, $timer->getName());
        $this->assertEquals(false, $timer->isStarted());
        $this->assertEquals(true, $timer->isNotStarted());
        $this->assertEquals(false, $timer->isStopped());
        $this->assertEquals(true, $timer->isNotStopped());
    }

    protected function setUp()
    {
        if (PHP_VERSION_ID < 70300) {
            $this->below73 = true;
        }
    }
}
