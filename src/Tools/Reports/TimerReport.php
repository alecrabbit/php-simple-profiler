<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 21:02
 */

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Reports\Base\Report;
use AlecRabbit\Tools\Timer;
use AlecRabbit\Tools\Traits\TimerFields;
use function AlecRabbit\format_time;
use const AlecRabbit\Constants\Accessories\DEFAULT_NAME;

class TimerReport extends Report
{
    use TimerFields;

    /**
     * TimerReport constructor.
     * @param Timer $timer
     */
    public function __construct(Timer $timer)
    {
        $count = $timer->getCount();
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

    /**
     * @return string
     */
    public function __toString(): string
    {
        if (DEFAULT_NAME === $name = $this->getName()) {
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
