<?php
/**
 * User: alec
 * Date: 30.11.18
 * Time: 17:42
 */

namespace AlecRabbit\Tools\Traits;

use AlecRabbit\Traits\GettableName;

trait CounterFields
{
    use GettableName;

    /** @var int */
    protected $value = 0;

    /** @var int */
    protected $max = 0;

    /** @var int */
    protected $min = 0;

    /** @var int */
    protected $path = 0;

    /** @var int */
    protected $length = 0;

    /** @var int */
    protected $initialValue = 0;

    /** @var int */
    protected $diff = 0;

    /** @var int */
    protected $step = 1;

    /** @var int */
    protected $bumpedForward = 0;

    /** @var int */
    protected $bumpedBack = 0;

    /** @var bool */
    protected $started = false;

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @return int
     */
    public function getMin(): int
    {
        return $this->min;
    }

    /**
     * @return int
     */
    public function getPath(): int
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @return int
     */
    public function getInitialValue(): int
    {
        return $this->initialValue;
    }

    /**
     * @return int
     */
    public function getDiff(): int
    {
        return $this->diff = $this->value - $this->initialValue;
    }


    /**
     * @return int
     */
    public function getStep(): int
    {
        return $this->step;
    }

    /**
     * @return int
     */
    public function getBumpedForward(): int
    {
        return $this->bumpedForward;
    }

    /**
     * @return int
     */
    public function getBumpedBack(): int
    {
        return $this->bumpedBack;
    }

    /**
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->started;
    }
}
