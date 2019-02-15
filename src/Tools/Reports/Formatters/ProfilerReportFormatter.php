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

    public function getString(): string
    {
        $r = '';
        $elapsed = '';
        foreach ($this->report->getCountersReports() as $report) {
            if ($report instanceof CounterReport && DEFAULT_NAME === $report->getName()) {
                $r .= $report->isStarted() ? $report : '';
            } else {
                $r .= $report;
            }
        }
        foreach ($this->report->getTimersReports() as $report) {
            if ($report instanceof TimerReport && DEFAULT_NAME === $report->getName()) {
                $elapsed .= $report;
            } else {
                $r .= $report;
            }
        }
        return $r . $elapsed;
    }
//    public function getString(): string
//    {
//        $r = '';
//        $elapsed = '';
//        foreach ($this->report->getReports() as $reports) {
//            foreach ($reports as $report) {
//                if ($report instanceof TimerReport && DEFAULT_NAME === $report->getName()) {
//                    $elapsed .= $report;
//                } elseif ($report instanceof CounterReport && DEFAULT_NAME === $report->getName()) {
//                    $r .= $report->isStarted() ? $report : '';
//                } else {
//                    $r .= $report;
//                }
//            }
//        }
//        return $r . $elapsed;
//    }
}
