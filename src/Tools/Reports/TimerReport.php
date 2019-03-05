<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 21:02
 */

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Reports\Core\OldReport;
use AlecRabbit\Tools\Timer;
use AlecRabbit\Tools\Traits\TimerFields;

class TimerReport extends OldReport
{
    use TimerFields;

    /**
     * TimerReport constructor.
     * @param Timer $timer
     */
    public function __construct(Timer $timer)
    {
        $this->name = $timer->getName();
        $this->creation = $timer->getCreation();
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
        $this->started = $timer->isStarted();
        $this->stopped = $timer->isStopped();
        $this->count = $count;
        parent::__construct();
    }
}
