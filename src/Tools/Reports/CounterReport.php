<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 21:02
 */

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Reports\Base\Report;
use AlecRabbit\Tools\Traits\CounterFields;

class CounterReport extends Report
{
    use CounterFields;

    /**
     * CounterReport constructor.
     * @param Counter $counter
     */
    public function __construct(Counter $counter)
    {
        $this->name = $counter->getName();
        $this->value = $counter->getValue();
        $this->step = $counter->getStep();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return
            sprintf(
                'Counter:[%s] Value: %s, Step: %s' . PHP_EOL,
                $this->getName(),
                $this->getValue(),
                $this->getStep()
            );
    }
}
