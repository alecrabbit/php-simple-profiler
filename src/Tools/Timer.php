<?php

namespace AlecRabbit\Tools;

use AlecRabbit\Pretty;
use AlecRabbit\Tools\Contracts\TimerInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Traits\Reportable;
use AlecRabbit\Tools\Traits\TimerFields;
use function AlecRabbit\format_time_auto;

class Timer implements TimerInterface, ReportableInterface
{
    use TimerFields, Reportable;

    /**
     * Timer constructor.
     * @param null|string $name
     */
    public function __construct(?string $name = null)
    {
        $this->name = $this->defaultName($name);
        $this->creation = $this->current();
    }

    /**
     * @return float
     */
    private function current(): float
    {
        return
            microtime(true);
    }

    public function prepareForReport(): void
    {
        if (null === $this->start) {
            $this->start();
            $this->mark();
        }
        $this->stop();
    }

    /**
     * Starts the timer.
     *
     * @return void
     */
    public function start(): void
    {
        $this->previous = $this->start = $this->current();
    }

    /**
     * @param int|null $iterationNumber
     */
    private function mark(?int $iterationNumber = null): void
    {
        $current = $this->current();
        $this->currentValue = $current - $this->previous;
        $this->previous = $current;

        if (0 !== $this->count) {
            ++$this->count;
            if ($this->currentValue < $this->minValue) {
                $this->minValue = $this->currentValue;
                $this->minValueIteration = $iterationNumber ?? $this->count;
            }
            if ($this->currentValue > $this->maxValue) {
                $this->maxValue = $this->currentValue;
                $this->maxValueIteration = $iterationNumber ?? $this->count;
            }
            $this->avgValue = (($this->avgValue * ($this->count - 1)) + $this->currentValue) / $this->count;
        } else {
            $this->maxValueIteration = $this->minValueIteration = $this->count = 1;
            $this->maxValue = $this->currentValue;
            $this->minValue = $this->currentValue;
            $this->avgValue = $this->currentValue;
        }
    }

    public function stop(): void
    {
        $this->elapsed = $this->current() - $this->creation;
        $this->stopped = true;
    }

    /**
     * Marks the elapsed time.
     * If timer was not started starts the timer.
     * @param int|null $iterationNumber
     * @return Timer
     */
    public function check(?int $iterationNumber = null): Timer
    {
        if (null === $this->start) {
            $this->start();
        } else {
            $this->mark($iterationNumber);
        }
        return $this;
    }

    /**
     * @param bool $pretty
     * @return mixed
     */
    public function elapsed(bool $pretty = true)
    {
        if ($this->isNotStopped()) {
            $this->stop();
        }
        return
            $pretty ? Pretty::time($this->getElapsed()) : $this->elapsed;
    }
}
