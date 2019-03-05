<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:18
 */

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Contracts\CounterInterface;
use AlecRabbit\Tools\Reports\Contracts\OldReportableInterface;
use AlecRabbit\Tools\Reports\Traits\OldReportable;
use AlecRabbit\Tools\Traits\CounterFields;

class Counter implements CounterInterface, OldReportableInterface
{
    use CounterFields, OldReportable;

    protected const DEFAULT_STEP = 1;

    /**
     * Counter constructor
     * @param null|string $name
     * @param null|int $step
     * @param int $initialValue
     */
    public function __construct(?string $name = null, ?int $step = null, int $initialValue = 0)
    {
        $this->name = $this->defaultName($name);
        $this->setInitialValue($initialValue);
        $this->setStep($step);
        $this->updateMaxAndMin();
    }

    /**
     * @param int $initialValue
     * @return Counter
     */
    public function setInitialValue(int $initialValue): Counter
    {
        if (false === $this->isStarted()) {
            $this->value = $this->initialValue = $this->length = $this->max = $this->min = $initialValue;
        } else {
            throw new \RuntimeException('You can\'t set counter initial value, it has been bumped already.');
        }
        return $this;
    }

    /**
     * @param null|int $step
     * @return Counter
     */
    public function setStep(?int $step = null): Counter
    {
        $step = $this->assertStep($step);
        if (false === $this->isStarted()) {
            $this->step = $step;
        } else {
            throw new \RuntimeException('You can\'t set counter step value, it has been bumped already.');
        }
        return $this;
    }

    /**
     * @param null|int $step
     * @return int
     */
    protected function assertStep(?int $step = null): int
    {
        $step = $step ?? self::DEFAULT_STEP;
        if ($step === 0) {
            throw new \RuntimeException('Counter step should be non-zero integer.');
        }
        return $step;
    }

    private function updateMaxAndMin(): void
    {
        if ($this->value > $this->max) {
            $this->max = $this->value;
        }
        if ($this->value < $this->min) {
            $this->min = $this->value;
        }
    }

    /**
     * @param int $times
     * @return int
     */
    public function bumpBack(int $times = 1): int
    {
        return
            $this->bump($times, false);
    }

    /**
     * @param int $times
     * @param bool $forward
     * @return int
     */
    public function bump(int $times = 1, bool $forward = true): int
    {
        $times = $this->assertTimes($times);
        $this->start();
        $this->path += $times * $this->step;
        $this->length += $times * $this->step;
        if ($forward) {
            $this->value += $times * $this->step;
            $this->bumpedForward++;
        } else {
            $this->value -= $times * $this->step;
            $this->bumpedBack++;
        }
        $this->updateMaxAndMin();
        return
            $this->value;
    }

    protected function assertTimes(int $times): int
    {
        if ($times < 1) {
            throw new
            \RuntimeException('Parameter 0 for bump() or bumpBack()  should be positive non-zero integer.');
        }
        return $times;
    }

    protected function start(): void
    {
        $this->started = true;
    }
}
