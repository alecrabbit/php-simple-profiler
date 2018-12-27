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
     * ProfilerReport constructor.
     * @param Profiler $profiler
     */
    public function __construct(Profiler $profiler)
    {
        foreach ($profiler->getCounters() as $counter) {
            $this->reports[static::_COUNTERS][$counter->getName()] = $counter->getReport();
        }
        foreach ($profiler->getTimers() as $timer) {
            $this->reports[static::_TIMERS][$timer->getName()] = $timer->getReport();
        }
        parent::__construct();
    }
}
