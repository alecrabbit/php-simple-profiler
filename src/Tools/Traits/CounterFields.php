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
    public function getStep(): int
    {
        return $this->step;
    }

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
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->started;
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
}
