<?php

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\HRTimer;
use AlecRabbit\Tools\Reports\HRTimerReport;
use PHPUnit\Framework\TestCase;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

/**
 * @group time-sensitive
 */
class HRTimerTest extends TestCase
{

    /**
     * @test
     * @throws \Exception
     */
    public function instance(): void
    {
        $timer = new HRTimer();
        $this->assertInstanceOf(HRTimer::class, $timer);
//        $this->assertInstanceOf(HRTimerReport::class, $timer->report());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerDefaults(): void
    {
        $timer = new HRTimer();
        $this->assertEquals(DEFAULT_NAME, $timer->getName());
        $this->assertEquals(0.0, $timer->getLastValue(), 'getLastValue');
        $this->assertEquals(0.0, $timer->getAverageValue(), 'getAvgValue');
        $this->assertEquals(null, $timer->getMinValue(), 'getMinValue');
        $this->assertEquals(null, $timer->getMaxValue(), 'getMaxValue');
        $this->assertEquals(0, $timer->getCount(), 'getCount');
        $this->assertEquals(0, $timer->getMinValueIteration(), 'getMinValueIteration');
        $this->assertEquals(0, $timer->getMaxValueIteration(), 'getMaxValueIteration');
        $this->assertInstanceOf(\DateInterval::class, $timer->getElapsed(), 'getElapsed');
//        $this->assertEquals($timer->getCreation(), $timer->getPrevious(), 'getCreation equals getPrevious');
        $this->assertEquals(true, $timer->isStarted());
        $this->assertEquals(false, $timer->isNotStarted());
        $this->assertEquals(false, $timer->isStopped());
        $this->assertEquals(true, $timer->isNotStopped());
    }

//    /**
//     * @test
//     * @throws \Exception
//     */
//    public function timerAvgValue(): void
//    {
//        $timer = new HRTimer();
//        $timer->start();
//        $count = 5;
//        for ($i = 0; $i < $count; $i++) {
//            sleep(1);
//            $timer->check();
//        }
//        $this->assertEquals(1.0, $timer->getAverageValue());
//        $this->assertEquals(1.0, $timer->getMinValue());
//        $this->assertEquals(1.0, $timer->getMaxValue());
//        $this->assertEquals(1.0, $timer->getLastValue());
//        $this->assertEquals($count, $timer->getCount());
//    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerAvgValueBounds(): void
    {
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


//        $timer = new HRTimer('name', false);
//        $this->assertEquals('name', $timer->getName());
//        $this->assertEquals(0.0, $timer->getLastValue(), 'getLastValue');
//        $this->assertEquals(0.0, $timer->getAverageValue(), 'getAvgValue');
//        $this->assertEquals(100000000.0, $timer->getMinValue(), 'getMinValue');
//        $this->assertEquals(0.0, $timer->getMaxValue(), 'getMaxValue');
//        $this->assertEquals(0, $timer->getCount(), 'getCount');
//        $this->assertEquals(0, $timer->getMinValueIteration(), 'getMinValueIteration');
//        $this->assertEquals(0, $timer->getMaxValueIteration(), 'getMaxValueIteration');
//        $this->assertEquals(0.0, $timer->getElapsed(), 'getElapsed');
//        $this->assertNotEquals($timer->getCreation(), $timer->getPrevious(), 'getCreation not equals getPrevious');
//        $this->assertEquals(0.0, $timer->getPrevious(), 'getPrevious');
//        $this->assertEquals(false, $timer->isStarted());
//        $this->assertEquals(true, $timer->isNotStarted());
//        $this->assertEquals(false, $timer->isStopped());
//        $this->assertEquals(true, $timer->isNotStopped());
    }
}
