<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\AbstractTimer;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Contracts\TimerReportInterface;
use AlecRabbit\Tools\Reports\Core\Report;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\Traits\TimerFields;
use function AlecRabbit\typeOf;

class TimerReport extends Report implements TimerReportInterface
{
    use TimerFields;

    protected static function getFormatter(): FormatterInterface
    {
        return Factory::getTimerReportFormatter();
    }

    /**
     * @param ReportableInterface $reportable
     * @return Contracts\ReportInterface
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function buildOn(ReportableInterface $reportable): ReportInterface
    {
        if ($reportable instanceof AbstractTimer) {
            $this->name = $reportable->getName();
            $this->creationTime = $reportable->getCreation();
            $this->count = $count = $reportable->getCount();
            $this->minValue = ($count === 1) ? $reportable->getLastValue() : $reportable->getMinValue();
            $this->maxValue = $reportable->getMaxValue();
            $this->maxValueIteration = $reportable->getMaxValueIteration();
            $this->minValueIteration = $reportable->getMinValueIteration();
            $this->started = $reportable->isStarted();
            $this->stopped = $reportable->isStopped();
            $this->avgValue = $reportable->getAverageValue();
            $this->currentValue = $reportable->getLastValue();
            $this->elapsed = $reportable->getElapsed();
            return $this;
        }
        throw new \RuntimeException(AbstractTimer::class . ' expected ' . typeOf($reportable) . ' given');
    }
}
