<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Factory;
use AlecRabbit\Tools\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Core\Report;

class ProfilerReport extends Report implements Strings
{
    /** @var array */
    private $reports = [];

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
        if ($profiler instanceof Profiler) {
            foreach ($profiler->getCounters() as $counter) {
                $this->reports[self::COUNTERS][$counter->getName()] = $counter->report();
            }
            foreach ($profiler->getTimers() as $timer) {
                $this->reports[self::TIMERS][$timer->getName()] = $timer->report();
            }
        } else {
            $this->wrongReportable(Profiler::class, $profiler);
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
