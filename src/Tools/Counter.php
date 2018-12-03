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
     * @param int $value
     */
    public function __construct(?string $name = null, int $value = 0)
    {
        $this->name = $this->default($name);
        $this->value = $value;
        $this->step = 1;
    }

    /**
     * @return int
     */
    public function bump(): int
    {
        return
            $this->bumpUp();
    }

    /**
     * @return int
     */
    public function bumpUp(): int
    {
        $this->value += $this->step;
        return
            $this->value;
    }

    /**
     * @param int $step
     * @param bool $setStep
     * @return int
     */
    public function bumpWith(int $step, bool $setStep = false): int
    {
        $this->value += $this->checkStep($step);
        if ($setStep) {
            $this->setStep($step);
        }
        return
            $this->value;
    }

    /**
     * @param int $step
     * @return int
     */
    private function checkStep(int $step): int
    {
        if ($step === 0) {
            throw new \RuntimeException('Counter step should be non-zero integer.');
        }
        return $step;
    }

    /**
     * @param int $step
     * @return Counter
     */
    public function setStep(int $step): Counter
    {
        $this->step = $this->checkStep($step);
        return $this;
    }

    /**
     * @return int
     */
    public function bumpDown(): int
    {
        $this->value -= $this->step;
        return
            $this->value;
    }
}
