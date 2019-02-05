<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:18
 */

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Contracts\CounterInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Traits\Reportable;
use AlecRabbit\Tools\Traits\CounterFields;

class Counter implements CounterInterface, ReportableInterface
{
    use CounterFields, Reportable;

    /**
     * Counter constructor
     * @param string|null $name
     * @param int $step
     * @param int $initialValue
     */
    public function __construct(?string $name = null, int $step = 1, int $initialValue = 0)
    {
        $this->name = $this->defaultName($name);
        $this->setInitialValue($initialValue);
        $this->setStep($step);
    }

    /**
     * @param int $initialValue
     * @return Counter
     */
    public function setInitialValue(int $initialValue): Counter
    {
        if (false === $this->isStarted()) {
            $this->value = $this->initialValue = $this->length = $initialValue;
        } else {
            throw new \RuntimeException('You can\'t set counter initial value, it has been bumped already.');
        }
        return $this;
    }

    /**
     * @param int $step
     * @return Counter
     */
    public function setStep(int $step): Counter
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
     * @param int $step
     * @return int
     */
    protected function assertStep(int $step): int
    {
        if ($step === 0) {
            throw new \RuntimeException('Counter step should be non-zero integer.');
        }
        return $step;
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
        return
            $this->value;
    }

    protected function assertTimes(int $times): int
    {
        if ($times < 1) {
            throw new \RuntimeException(__METHOD__ . ' parameter 0 should be positive non-zero integer.');
        }
        return $times;
    }

    protected function start(): void
    {
        $this->started = true;
    }
}
