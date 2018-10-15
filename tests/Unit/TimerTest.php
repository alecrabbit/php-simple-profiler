<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 23:03
 */

namespace Unit;


use AlecRabbit\Exception\RuntimeException;
use AlecRabbit\Profiler\Timer;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class TimerTest extends TestCase
{
    /** @test */
    public function ClassCreation()
    {
        $timer = new Timer();
        $this->assertInstanceOf(Timer::class, $timer);
    }

    /** @test */
    public function TimerDefaultCreation()
    {
        $timer = new Timer();
        $this->assertEquals('default', $timer->getName());
        $timer = new Timer('name');
        $this->assertEquals('name', $timer->getName());
    }

    /** @test */
    public function TimerAvgValue()
    {
        $timer = (new Timer())->forceStart();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            usleep(1000);
            $timer->check();
        }
        $this->assertEquals(0.001, $timer->getAvgValue(), 'getAvgValue', 0.0002);
        $this->assertEquals(0.001, $timer->getMinValue(), 'getMinValue', 0.0002);
        $this->assertEquals(0.001, $timer->getMaxValue(), 'getMaxValue', 0.0002);
        $this->assertEquals(0.001, $timer->getCurrentValue(), 'getCurrentValue', 0.0002);
        $this->assertEquals($count, $timer->getCount());
    }

    /** @test */
    public function TimerElapsedNotStarted()
    {
        $timer = new Timer();
        $this->expectException(RuntimeException::class);
        $this->assertEquals(1, $timer->elapsed());
    }

    /** @test */
    public function TimerValuesNotStarted()
    {
        $timer = new Timer();
        $this->expectException(RuntimeException::class);
        $this->assertEquals(
            [
                'Last' => null,
                'Avg' => null,
                'Min' => null,
                'Max' => null,
                'Count' => null,
            ],
            $timer->getTimerValues()
        );
    }

    /** @test */
    public function TimerElapsed()
    {
        $timer = new Timer();
        $timer->start();
        usleep(1000);
        $this->assertEquals(0.001, $timer->elapsed(), 'Elapsed time', 0.0002);
        $this->assertStringMatchesFormat(
            '%fms',
            $timer->elapsed(true)
        );
    }

    /** @test */
    public function TimerValues()
    {
        $timer = new Timer();
        $timer->start();
        $count = 7;
        for ($i = 0; $i < $count; $i++) {
            usleep(1000);
            $timer->check();
        }
        $this->assertEquals(
            [
                Timer::_LAST=> '1ms',
                Timer::_AVG => '1ms',
                Timer::_MIN => '1ms',
                Timer::_MAX => '1ms',
                Timer::_COUNT => $count,

            ],
            $timer->getTimerValues(true, Timer::UNIT_MILLISECONDS, 0)
        );
    }

    /** @test
     * @throws \ReflectionException
     */
    public function TimerFormatPrivate()
    {
        $method = new ReflectionMethod(Timer::class, 'format');
        $method->setAccessible(true);

        $timer = new Timer();

        $this->assertEquals('11.1ms', $method->invoke($timer, 0.0111));
        $this->assertEquals('11100μs', $method->invoke($timer, 0.0111, Timer::UNIT_MICROSECONDS));
        $this->assertEquals('22.342342μs', $method->invoke($timer, 0.000022342342342, Timer::UNIT_MICROSECONDS, 6));
        $this->assertEquals('1000022.23435μs', $method->invoke($timer, 1.00002223435, Timer::UNIT_MICROSECONDS, 6));
        $this->assertEquals('1000.022ms', $method->invoke($timer, 1.000022, Timer::UNIT_MILLISECONDS));
        $this->assertEquals('10.000022s', $method->invoke($timer, 10.000022, Timer::UNIT_SECONDS, 6));
        $this->assertEquals('0.17m', $method->invoke($timer, 10.000022, Timer::UNIT_MINUTES, 2));
        $this->assertEquals('0.002778h', $method->invoke($timer, 10.000022, Timer::UNIT_HOURS, 6));
    }

}