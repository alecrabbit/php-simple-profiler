<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 23:03
 */

namespace Unit;


use AlecRabbit\Profiler\Timer;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Class TimerTest
 * @group time-sensitive
 */
class TimerTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        ClockMock::register(Timer::class);
        ClockMock::withClockMock(true);
    }

    public static function tearDownAfterClass(): void
    {
        ClockMock::withClockMock(false);
    }

    /** @test */
    public function ClassCreation(): void
    {
        $timer = new Timer();
        $this->assertInstanceOf(Timer::class, $timer);
    }

    /** @test */
    public function TimerDefaultCreation(): void
    {
        $timer = new Timer();
        $this->assertEquals('default', $timer->getName());
        $timer = new Timer('name');
        $this->assertEquals('name', $timer->getName());
    }

    /**
     * @test
     */
    public function TimerAvgValue(): void
    {
        $timer = (new Timer())->forceStart();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            sleep(1);
            $timer->check();
        }
        $this->assertEquals(1.0, $timer->getAvgValue(), 'getAvgValue', 0.0004);
        $this->assertEquals(1.0, $timer->getMinValue(), 'getMinValue', 0.0004);
        $this->assertEquals(1.0, $timer->getMaxValue(), 'getMaxValue', 0.0004);
        $this->assertEquals(1.0, $timer->getCurrentValue(), 'getCurrentValue', 0.0004);
        $this->assertEquals($count, $timer->getCount());
    }

    /** @test */
    public function TimerElapsedNotStarted(): void
    {
        $timer = new Timer();
        $this->expectException(\RuntimeException::class);
        $this->assertEquals(1, $timer->elapsed());
    }

    /** @test */
    public function TimerValuesNotStarted(): void
    {
        $timer = new Timer();
        $this->expectException(\RuntimeException::class);
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
    public function TimerElapsed(): void
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
    public function TimerValues(): void
    {
        $timer = new Timer();
        $timer->start();
        $count = 7;
        for ($i = 0; $i < $count; $i++) {
            usleep(2000 + $i * 1000);
            $timer->check();
        }
        usleep(1000);
        $timer->check();
        $this->assertEquals(
            [
                Timer::_LAST => '1ms',
                Timer::_AVG => '5ms',
                Timer::_MIN => '1ms',
                Timer::_MAX => '8ms',
                Timer::_COUNT => ++$count,

            ],
            $timer->getTimerValues(true, Timer::UNIT_MILLISECONDS, 0)
        );
    }

    /** @test */
    public function TimerValuesTwo(): void
    {
        $timer = new Timer();
        $timer->start();
        $count = 7;
        for ($i = 0; $i < $count; $i++) {
            usleep(2000 + $i * 1000);
            $timer->check();
        }
        usleep(1000);
        $timer->check();
        $expected = [
            Timer::_LAST => 0.001,
            Timer::_AVG => 0.005,
            Timer::_MIN => 0.001,
            Timer::_MAX => 0.008,
            Timer::_COUNT => ++$count,

        ];
        $actual = $timer->getTimerValues(false);
        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $actual[$key], '', 0.0005);
        }
    }

    /** @test
     * @throws \ReflectionException
     */
    public function TimerFormatPrivate(): void
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