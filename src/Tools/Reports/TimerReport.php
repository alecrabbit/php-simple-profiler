<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\AbstractTimer;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Contracts\TimerReportInterface;
use AlecRabbit\Tools\Reports\Core\Report;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\Traits\TimerFields;

class TimerReport extends Report implements TimerReportInterface
{
    use TimerFields;

    /**
     * TimerReport constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        // This lines here keep vimeo/psalm quiet
        $this->creationTime = new \DateTimeImmutable();
        $this->elapsed = (new \DateTimeImmutable())->diff($this->creationTime);
    }

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
        } else {
            $this->wrongReportable( AbstractTimer::class, $reportable);
        }
        return $this;
    }
}
