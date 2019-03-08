<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 21:02
 */

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Contracts\CounterInterface;
use AlecRabbit\Tools\ExtendedCounter;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Core\Report;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\Traits\ExtendedCounterFields;
use function AlecRabbit\typeOf;

class CounterReport extends Report
{
    use ExtendedCounterFields;

    /**
     * CounterReport constructor.
     * @param ExtendedCounter $counter
     */
    public function __construct(ExtendedCounter $counter)
    {
    }

    protected static function getFormatter(): FormatterInterface
    {
        return Factory::getTimerReportFormatter();
    }

    /**
     * @param ReportableInterface $counter
     * @return Contracts\ReportInterface
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function buildOn(ReportableInterface $counter): ReportInterface
    {
        if ($counter instanceof CounterInterface) {
            $this->name = $counter->getName();
            $this->value = $counter->getValue();
            $this->max = $counter->getMax();
            $this->min = $counter->getMin();
            $this->path = $counter->getPath();
            $this->length = $counter->getLength();
            $this->step = $counter->getStep();
            $this->started = $counter->isStarted();
            $this->diff = $counter->getDiff();
            $this->initialValue = $counter->getInitialValue();
            $this->bumpedForward = $counter->getBumpedForward();
            $this->bumpedBack = $counter->getBumpedBack();
            return $this;
        }
        throw new \RuntimeException(CounterInterface::class . ' expected ' . typeOf($counter) . ' given');
    }
}
