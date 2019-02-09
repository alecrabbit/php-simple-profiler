<?php
/**
 * User: alec
 * Date: 01.12.18
 * Time: 17:36
 */

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Contracts\StringsInterface;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Base\Report;

class ProfilerReport extends Report implements StringsInterface
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
            $this->reports[self::COUNTERS][$counter->getName()] = $counter->getReport();
        }
        foreach ($profiler->getTimers() as $timer) {
            $this->reports[self::TIMERS][$timer->getName()] = $timer->getReport();
        }
        parent::__construct();
    }
}
