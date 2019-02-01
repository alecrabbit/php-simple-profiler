<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 21:02
 */

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Exception\InvalidStyleException;
use AlecRabbit\Tools\Reports\Base\Report;
use AlecRabbit\Tools\Timer;
use AlecRabbit\Tools\Traits\TimerFields;

class TimerReport extends Report
{
    use TimerFields;

    /**
     * TimerReport constructor.
     * @param Timer $timer
     * @throws InvalidStyleException
     */
    public function __construct(Timer $timer)
    {
        $this->name = $timer->getName();
        $this->creation = $timer->getCreation();
        try {
            $count = $timer->getCount();
            $this->previous = $timer->getPrevious();
            $this->elapsed = $timer->getElapsed();
            $this->stopped = $timer->isStopped();
            $this->currentValue = $timer->getLastValue();
            $this->minValueIteration = $timer->getMinValueIteration();
            $this->maxValueIteration = $timer->getMaxValueIteration();
            $this->avgValue = $timer->getAverageValue();
            $this->minValue = ($count === 1) ? $timer->getLastValue() : $timer->getMinValue();
            $this->maxValue = $timer->getMaxValue();
            $this->count = $count;
        } catch (\Throwable $e) {
            // no further action
        }
        parent::__construct();
    }
}
