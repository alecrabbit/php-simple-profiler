<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Core\Report;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;

class ProfilerReport extends Report implements Strings
{
    /** @var array */
    private $reports = [];

    /**
     * ProfilerReport constructor.
     * @param Profiler $profiler
     * @throws \Exception
     */
    public function __construct(Profiler $profiler)
    {
    }

    protected static function getFormatter(): FormatterInterface
    {
        return Factory::getProfilerReportFormatter();
    }

    /**
     * @return array
     */
    public function getReports(): array
    {
        return $this->reports;
    }

    public function buildOn(ReportableInterface $profiler): ReportInterface
    {
        /** @var Profiler $profiler */
        foreach ($profiler->getCounters() as $counter) {
            $this->reports[self::COUNTERS][$counter->getName()] = $counter->report();
        }
        foreach ($profiler->getTimers() as $timer) {
            $this->reports[self::TIMERS][$timer->getName()] = $timer->report();
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getCountersReports(): array
    {
        return $this->reports[self::COUNTERS];
    }

    /**
     * @return array
     */
    public function getTimersReports(): array
    {
        return $this->reports[self::TIMERS];
    }
}
