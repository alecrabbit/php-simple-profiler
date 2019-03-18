<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Core\Report;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\SimpleCounter;
use AlecRabbit\Tools\Traits\SimpleCounterFields;

abstract class AbstractCounterReport extends Report
{
    use SimpleCounterFields;

    protected static function getFormatter(): FormatterInterface
    {
        return Factory::getSimpleCounterReportFormatter();
    }

    /**
     * @param ReportableInterface $counter
     * @return ReportInterface
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
        } else {
            $this->wrongReportable(SimpleCounter::class, $counter);
        }
        return $this;
    }
}
