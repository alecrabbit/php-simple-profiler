<?php
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\CounterReport;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\TimerReport;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class ProfilerReportFormatter extends ReportFormatter
{
    /** {@inheritdoc} */
    public function process(ReportInterface $report): string
    {
        return
            sprintf(
                '%s %s %s ',
                $this->countersStrings($report),
                $this->timersStrings(),
                $this->elapsed
            );
    }

    /**
     * @param ReportInterface $report
     * @return string
     */
    protected function countersStrings(ReportInterface $report): string
    {
        /** @var ProfilerReport $report */
        $r = '';
        foreach ($report->getCountersReports() as $countersReport) {
            if ($countersReport instanceof CounterReport && DEFAULT_NAME === $countersReport->getName()) {
                $r .= $countersReport->isStarted() ? $countersReport : '';
            } else {
                $r .= $countersReport;
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
