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
    protected $step = 1;

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
}
