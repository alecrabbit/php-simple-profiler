<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 21:02
 */

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Exception\InvalidStyleException;
use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Reports\Base\Report;
use AlecRabbit\Tools\Traits\CounterFields;

class CounterReport extends Report
{
    use CounterFields;

    /**
     * CounterReport constructor.
     * @param Counter $counter
     * @throws InvalidStyleException
     */
    public function __construct(Counter $counter)
    {
        $this->name = $counter->getName();
        $this->value = $counter->getValue();
        $this->step = $counter->getStep();
        parent::__construct();
    }
}
