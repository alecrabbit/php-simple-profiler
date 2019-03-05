<?php
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Reports\CounterReport;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\TimerReport;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class ProfilerReportFormatter extends ReportFormatter
{
    /** @var ProfilerReport */
    protected $report;

    /** @var string */
    protected $elapsed = '';

    public function process(): string
    {
        return
            sprintf(
                '%s %s %s ',
                $this->countersStrings(),
                $this->timersStrings(),
                $this->elapsed
            );
    }

    /**
     * @return string
     */
    protected function countersStrings(): string
    {
        $r = '';
        foreach ($this->report->getCountersReports() as $report) {
            if ($report instanceof CounterReport && DEFAULT_NAME === $report->getName()) {
                $r .= $report->isStarted() ? $report : '';
            } else {
                $r .= $report;
            }
        }
        return $r;
    }

    /**
     * @return string
     */
    protected function timersStrings(): string
    {
        $r = '';
        foreach ($this->report->getTimersReports() as $report) {
            if ($report instanceof TimerReport && DEFAULT_NAME === $report->getName()) {
                $this->elapsed = (string)$report;
            } else {
                $r .= $report;
            }
        }
        return $r;
    }
}
