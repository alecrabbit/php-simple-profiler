<?php

namespace AlecRabbit\Tools;

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Tools\Contracts\TimerInterface;
use AlecRabbit\Tools\Reports\Contracts\OldReportableInterface;
use AlecRabbit\Tools\Reports\Traits\OldReportable;
use AlecRabbit\Tools\Traits\TimerFields;

class Timer implements TimerInterface, OldReportableInterface
{
    use TimerFields, OldReportable;

    /**
     * Timer constructor.
     * @param null|string $name
     * @param bool $start
     */
    public function __construct(?string $name = null, bool $start = true)
    {
        $this->name = $this->defaultName($name);
        $this->creation = $this->current();
        if ($start) {
            $this->start($this->creation);
        }
    }

    /**
     * @return float
     */
    public function current(): float
    {
        return
            microtime(true);
    }

    /**
     * Starts the timer.
     *
     * @param null|float $point
     * @return void
     */
    public function start(?float $point = null): void
    {
        $this->previous = $point ?? $this->current();
        $this->started = true;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareForReport(): void
    {
        if ($this->isNotStarted()) {
            $this->start();
            $this->mark();
        }
        $this->stop();
    }

    /**
     * @param int|null $iterationNumber
     */
    private function mark(?int $iterationNumber = null): void
    {
        $current = $this->current();
        $this->currentValue = $current - $this->previous;
        $this->previous = $current;

        $this->compute($iterationNumber);
    }

    /**
     * @param null|int $iterationNumber
     */
    private function compute(?int $iterationNumber): void
    {
        if (0 !== $this->count) {
            ++$this->count;
            $this->checkMinValue($iterationNumber);
            $this->checkMaxValue($iterationNumber);
            $this->computeAverage();
        } else {
            $this->initValues();
        }
    }

    /**
     * @param null|int $iterationNumber
     */
    private function checkMinValue(?int $iterationNumber): void
    {
        if ($this->currentValue < $this->minValue) {
            $this->minValue = $this->currentValue;
            $this->minValueIteration = $iterationNumber ?? $this->count;
        }
    }

    /**
     * @param null|int $iterationNumber
     */
    private function checkMaxValue(?int $iterationNumber): void
    {
        if ($this->currentValue > $this->maxValue) {
            $this->maxValue = $this->currentValue;
            $this->maxValueIteration = $iterationNumber ?? $this->count;
        }
    }

    private function computeAverage(): void
    {
        $this->avgValue = (($this->avgValue * ($this->count - 1)) + $this->currentValue) / $this->count;
    }

    private function initValues(): void
    {
        $this->maxValueIteration = $this->minValueIteration = $this->count = 1;
        $this->maxValue = $this->currentValue;
        $this->minValue = $this->currentValue;
        $this->avgValue = $this->currentValue;
    }

    public function stop(): void
    {
        $this->computeElapsed();
        $this->stopped = true;
    }

    private function computeElapsed(): void
    {
        $this->elapsed = $this->current() - $this->creation;
    }

    /**
     * Marks the time.
     * If timer was not started starts the timer.
     * @param int|null $iterationNumber
     * @return Timer
     */
    public function check(?int $iterationNumber = null): Timer
    {
        if ($this->isNotStarted()) {
            $this->start();
        } else {
            $this->mark($iterationNumber);
        }
        return $this;
    }

    /**
     * @param float $start
     * @param float $stop
     * @param null|int $iterationNumber
     * @return Timer
     */
    public function bounds(float $start, float $stop, ?int $iterationNumber = null): Timer
    {
        if ($this->isNotStarted()) {
            $this->start();
        }
        $this->currentValue = $stop - $start;
        $this->previous = $stop;

        $this->compute($iterationNumber);
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
            $pretty ? Pretty::seconds($this->getElapsed()) : $this->elapsed;
    }

    /**
     * @return float
     */
    public function getElapsed(): float
    {
        if ($this->isNotStopped()) {
            $this->computeElapsed();
        }
        return $this->elapsed;
    }
}
