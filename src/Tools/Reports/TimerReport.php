<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 21:02
 */

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Constants;
use AlecRabbit\Tools\Reports\Base\Report;
use AlecRabbit\Tools\Timer;
use AlecRabbit\Tools\Traits\TimerFields;

class TimerReport extends Report
{
    use TimerFields;

    public function __construct(Timer $timer)
    {
        if (0 === $count = $timer->getCount()) {
            throw new \RuntimeException('Timer "' . $timer->getName() . '" has not been started.');
        }
        $this->name = $timer->getName();
        $this->previous = $timer->getPrevious();
        $this->creation = $timer->getCreation();
        $this->start = $timer->getStart();
        $this->elapsed = $timer->getElapsed();
        $this->stopped = $timer->isStopped();
        $this->currentValue = $timer->getLastValue();
        $this->minValueIteration = $timer->getMinValueIteration();
        $this->maxValueIteration = $timer->getMaxValueIteration();
        $this->avgValue = $timer->getAverageValue();
        $this->minValue = ($count === 1) ? $timer->getLastValue() : $timer->getMinValue();
        $this->maxValue = $timer->getMaxValue();
        $this->count = $count;
    }

    public function __toString(): string
    {
        if (Constants::DEFAULT_NAME === $name = $this->getName()) {
            return
                sprintf(
                    'Timer:[%s] Elapsed: %s' . PHP_EOL,
                    $name,
                    format_time($this->getElapsed())
                );
        }
        return
            sprintf(
                'Timer:[%s] Average: %s, Last: %s, Min(%s): %s, Max(%s): %s, Count: %s' . PHP_EOL,
                $name,
                format_time($this->getAverageValue()),
                format_time($this->getLastValue()),
                $this->getMinValueIteration(),
                format_time($this->getMinValue()),
                $this->getMaxValueIteration(),
                format_time($this->getMaxValue()),
                $this->getCount()
            );
    }
}
