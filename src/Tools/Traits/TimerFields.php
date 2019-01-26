<?php

namespace AlecRabbit\Tools\Traits;

use AlecRabbit\Traits\GettableName;

trait TimerFields
{
    use GettableName;

    /** @var float */
    protected $previous;

    /** @var float */
    protected $start;

    /** @var float */
    protected $creation;

    /** @var float */
    protected $elapsed;

    /** @var bool */
    protected $stopped = false;

    /** @var bool */
    protected $started = false;

    /** @var float */
    protected $currentValue;

    /** @var float */
    protected $avgValue;

    /** @var float */
    protected $minValue;

    /** @var int */
    protected $minValueIteration = 0;

    /** @var float */
    protected $maxValue;

    /** @var int */
    protected $maxValueIteration = 0;

    /** @var int */
    protected $count = 0;

    /**
     * @return bool
     */
    public function isStopped(): bool
    {
        return $this->stopped;
    }

    /**
     * @return bool
     */
    public function isNotStopped(): bool
    {
        return !$this->stopped;
    }

    /**
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->started;
    }

    /**
     * @return bool
     */
    public function isNotStarted(): bool
    {
        return !$this->started;
    }

    /**
     * @return float
     */
    public function getLastValue(): float
    {
        return $this->currentValue;
    }

    /**
     * @return float
     */
    public function getAverageValue(): float
    {
        return $this->avgValue;
    }

    /**
     * @return float
     */
    public function getMinValue(): float
    {
        return $this->minValue;
    }

    /**
     * @return float
     */
    public function getMaxValue(): float
    {
        return $this->maxValue;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getMinValueIteration(): int
    {
        return $this->minValueIteration;
    }

    /**
     * @return int
     */
    public function getMaxValueIteration(): int
    {
        return $this->maxValueIteration;
    }

    /**
     * @return float
     */
    public function getElapsed(): float
    {
        return $this->elapsed;
    }

    /**
     * @return float
     */
    public function getCreation(): float
    {
        return $this->creation;
    }

    /**
     * @return float
     */
    public function getPrevious(): float
    {
        return $this->previous;
    }

    /**
     * @return float
     */
    public function getStart(): float
    {
        return $this->start;
    }
}
