<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Reports\Core\AbstractReport;
use AlecRabbit\Reports\Core\AbstractReportable;
use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Profiler;

class ProfilerReport extends AbstractReport implements Strings
{
    /** @var array */
    private $reports = [];

    /**
     * @return array
     */
    public function getReports(): array
    {
        return $this->reports;
    }

    /** {@inheritDoc}
     * @throws \Exception
     */
    protected function extractDataFrom(AbstractReportable $reportable = null): void
    {
        if ($reportable instanceof Profiler) {
            foreach ($reportable->getCounters() as $counter) {
                $this->reports[self::COUNTERS][$counter->getName()] = $counter->report();
            }
            foreach ($reportable->getTimers() as $timer) {
                $this->reports[self::TIMERS][$timer->getName()] = $timer->report();
            }
        }
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
