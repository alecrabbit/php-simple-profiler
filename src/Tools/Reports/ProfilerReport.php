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
     * @param Profiler $reportable
     */
    public function __construct(Profiler $reportable)
    {
        foreach ($reportable->getCounters() as $counter) {
            $this->reports[static::_COUNTERS][$counter->getName()] = $counter->report();
        }
        foreach ($reportable->getTimers() as $timer) {
            $this->reports[static::_TIMERS][$timer->getName()] = $timer->report();
        }
    }

    public function __toString(): string
    {
        $r = '';
        foreach ($this->reports as $reports) {
            foreach ($reports as $report) {
                $r .= $report;
            }
        }
        return $r;
    }
}
