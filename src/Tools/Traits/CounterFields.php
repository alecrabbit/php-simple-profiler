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
    protected $value;

    /** @var int */
    protected $step;

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
}
