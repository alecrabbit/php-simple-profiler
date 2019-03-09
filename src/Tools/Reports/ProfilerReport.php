<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Core\Report;

class ProfilerReport extends Report implements Strings
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

    /**
     * ProfilerReport constructor.
     * @param Profiler $profiler
     */
    public function __construct(Profiler $profiler)
    {
        foreach ($profiler->getCounters() as $counter) {
            $this->reports[self::COUNTERS][$counter->getName()] = $counter->report();
        }
        foreach ($profiler->getTimers() as $timer) {
            $this->reports[self::TIMERS][$timer->getName()] = $timer->report();
        }
        parent::__construct();
    }
}
