<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\ExtendedCounter;
use AlecRabbit\Tools\Factory;
use AlecRabbit\Tools\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Core\Report;
use AlecRabbit\Tools\Traits\ExtendedCounterFields;
use AlecRabbit\Tools\Traits\SimpleCounterFields;

class ExtendedCounterReport extends Report
{
    use ExtendedCounterFields, SimpleCounterFields;

    protected static function getFormatter(): FormatterInterface
    {
        return Factory::getExtendedCounterReportFormatter();
    }

    /**
     * @param ReportableInterface $counter
     * @return ReportInterface
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function buildOn(ReportableInterface $counter): ReportInterface
    {
        if ($counter instanceof ExtendedCounter) {
            $this->name = $counter->getName();
            $this->value = $counter->getValue();
            $this->step = $counter->getStep();
            $this->started = $counter->isStarted();
            $this->initialValue = $counter->getInitialValue();
            $this->bumped = $counter->getBumped();
            $this->max = $counter->getMax();
            $this->min = $counter->getMin();
            $this->path = $counter->getPath();
            $this->length = $counter->getLength();
            $this->diff = $counter->getDiff();
            $this->bumpedBack = $counter->getBumpedBack();
        } else {
            $this->wrongReportable(ExtendedCounter::class, $counter);
        }
        return $this;
    }
}
