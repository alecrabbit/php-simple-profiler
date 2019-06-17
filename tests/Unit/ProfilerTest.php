<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Traits;

use AlecRabbit\Counters\Core\AbstractCounter;
use AlecRabbit\Timers\Core\AbstractTimer;
use AlecRabbit\Tools\Profiler;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class ProfilerTest extends TestCase
{

    /**
     * @test
     * @throws \Exception
     */
    public function classCreation(): void
    {
        $profiler = new Profiler();
        $name = 'name';
        $profiler->counter($name);
        $profiler->timer($name);
        $this->assertInstanceOf(Profiler::class, $profiler);
        $counters = $profiler->getCounters();
        foreach ($counters as $counter) {
            $this->assertInstanceOf(AbstractCounter::class, $counter);
        }
        $timers = $profiler->getTimers();
        foreach ($timers as $timer) {
            $this->assertInstanceOf(AbstractTimer::class, $timer);
        }
    }

    /**
     * @test
     * @throws \Exception
     */
    public function counterCreation(): void
    {
        $profiler = new Profiler();
        $profiler->counter('new')->bump();
        $this->assertEquals(1, $profiler->counter('new')->getValue());
        $profiler->counter('new', 'vol', 'buy', 'tor')->bump();
        $this->assertStringMatchesFormat(
            '%s [%s, %s, %s]',
            $profiler
                ->counter('new', 'vol', 'buy', 'tor')
                ->getName()
        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerCreation(): void
    {
        $profiler = new Profiler();
        $new = 'new';
        $profiler->counter($new)->bump();
        $profiler->timer($new)->start();
        $profiler->timer($new)->check();
        $this->assertIsString($profiler->timer($new)->elapsed());
        $this->assertStringMatchesFormat(
            '%s [%s, %s, %s]',
            $profiler
                ->timer($new, 'vol', 'buy', 'tor')
                ->getName()
        );
    }
}
