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
        $this->value = $this->initialValue = $initialValue;
        $this->step = $step;
    }

    /**
     * @param int $initialValue
     * @return Counter
     */
    public function setInitialValue(int $initialValue): Counter
    {
        if (false === $this->started) {
            $this->value = $this->initialValue = $initialValue;
        } else {
            throw new \RuntimeException('You can not set counter start value, it has been bumped already.');
        }
        return $this;
    }

    /**
     * @param int $step
     * @return Counter
     */
    public function setStep(int $step): Counter
    {
        $this->step = $this->assertStep($step);
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
     * @return int
     */
    public function bumpBack(): int
    {
        return
            $this->bump(false);
//        $this->start();
//        $this->value -= $this->step;
//        return
//            $this->value;
    }

    /**
     * @param bool $forward
     * @return int
     */
    public function bump(bool $forward = true): int
    {
        $this->start();
        if ($forward) {
            $this->value += $this->step;
            $this->bumpedForward++;
        } else {
            $this->value -= $this->step;
            $this->bumpedBack++;
        }
        return
            $this->value;
    }

    protected function start(): void
    {
        $this->started = true;
    }
}
