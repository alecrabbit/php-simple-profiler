<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\AbstractTimer;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\TimerReportInterface;
use AlecRabbit\Tools\Reports\Core\Report;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\Traits\HasStartAndStop;
use AlecRabbit\Tools\Traits\TimerFields;
use function AlecRabbit\typeOf;

class TimerReport extends Report implements TimerReportInterface
{
    use TimerFields, HasStartAndStop;

    protected static function getFormatter(): FormatterInterface
    {
        return Factory::getTimerReportFormatter();
    }

    /**
     * @param ReportableInterface $reportable
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function buildOn(ReportableInterface $reportable): void
    {
        if ($reportable instanceof AbstractTimer) {
            $this->name = $reportable->getName();
            $this->creationTime = $reportable->getCreation();
            $count = $reportable->getCount();
            $this->elapsed = $reportable->getElapsed();
            $this->stopped = $reportable->isStopped();
            $this->currentValue = $reportable->getLastValue();
            $this->minValueIteration = $reportable->getMinValueIteration();
            $this->maxValueIteration = $reportable->getMaxValueIteration();
            $this->avgValue = $reportable->getAverageValue();
            $this->minValue = ($count === 1) ? $reportable->getLastValue() : $reportable->getMinValue();
            $this->maxValue = $reportable->getMaxValue();
            $this->started = $reportable->isStarted();
            $this->stopped = $reportable->isStopped();
            $this->count = $count;
        } else {
            throw new \RuntimeException(AbstractTimer::class . ' expected ' . typeOf($reportable) . ' given');
        }
    }
}
