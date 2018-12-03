<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:19
 */

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Contracts\TimerInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Traits\Reportable;
use AlecRabbit\Tools\Traits\TimerFields;

class Timer implements TimerInterface, ReportableInterface
{
    use TimerFields, Reportable;

    /**
     * Timer constructor.
     * @param null|string $name
     */
    public function __construct(?string $name = null)
    {
        $this->name = $this->default($name);
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
            if ($this->currentValue < $this->minValue) {
                $this->minValue = $this->currentValue;
                if ($iterationNumber) {
                    $this->minValueIteration = $iterationNumber;
                }
            }
            if ($this->currentValue > $this->maxValue) {
                $this->maxValue = $this->currentValue;
                if ($iterationNumber) {
                    $this->maxValueIteration = $iterationNumber;
                }
            }
            $this->avgValue = (($this->avgValue * $this->count) + $this->currentValue) / ++$this->count;
        } else {
            $this->count = 1;
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
     * @param bool $formatted
     * @return mixed
     */
    public function elapsed(bool $formatted = true)
    {
        if ($this->isNotStopped()) {
            $this->stop();
        }
        return
            $formatted ? format_time($this->getElapsed()) : $this->elapsed;
    }
}
