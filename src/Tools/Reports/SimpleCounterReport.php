<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\AbstractCounter;
use AlecRabbit\Tools\Contracts\CounterInterface;
use AlecRabbit\Tools\ExtendedCounter;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Core\Report;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\SimpleCounter;
use AlecRabbit\Tools\Traits\ExtendedCounterFields;
use AlecRabbit\Tools\Traits\CounterFields;
use function AlecRabbit\typeOf;

class SimpleCounterReport extends Report
{
    use ExtendedCounterFields, CounterFields;

    protected static function getFormatter(): FormatterInterface
    {
        return Factory::getCounterReportFormatter();
    }

    /**
     * @param ReportableInterface $counter
     * @return Contracts\ReportInterface
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function buildOn(ReportableInterface $counter): ReportInterface
    {
        if ($counter instanceof SimpleCounter) {
            $this->name = $counter->getName();
            $this->value = $counter->getValue();
            $this->step = $counter->getStep();
            $this->started = $counter->isStarted();
            $this->initialValue = $counter->getInitialValue();
            $this->bumped = $counter->getBumped();
            if ($counter instanceof ExtendedCounter) {
                $this->max = $counter->getMax();
                $this->min = $counter->getMin();
                $this->path = $counter->getPath();
                $this->length = $counter->getLength();
                $this->diff = $counter->getDiff();
                $this->bumpedBack = $counter->getBumpedBack();
            }
            return $this;
        }
        throw new \RuntimeException(AbstractCounter::class . ' instance expected ' . typeOf($counter) . ' given');
    }
}
