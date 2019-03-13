<?php
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\SimpleCounterReport;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\TimerReport;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class ProfilerReportFormatter extends ReportFormatter
{
    /** @var string */
    private $elapsed = '';

    /** {@inheritdoc} */
    public function process(ReportInterface $report): string
    {
        if ($report instanceof ProfilerReport) {
            return
                sprintf(
                    '%s %s %s',
                    $this->countersStrings($report),
                    $this->timersStrings($report),
                    $this->elapsed
                );
        }
        return '';
    }

    /**
     * @param ProfilerReport $report
     * @return string
     */
    protected function countersStrings(ProfilerReport $report): string
    {
        $r = '';
        foreach ($report->getCountersReports() as $countersReport) {
            if ($countersReport instanceof SimpleCounterReport && DEFAULT_NAME === $countersReport->getName()) {
                $r .= $countersReport->isStarted() ? $countersReport : '';
            } else {
                $r .= $countersReport;
            }
        }
        return $r;
    }

    /**
     * @param ProfilerReport $report
     * @return string
     */
    protected function timersStrings(ProfilerReport $report): string
    {
        $r = '';
        foreach ($report->getTimersReports() as $timerReport) {
            if ($timerReport instanceof TimerReport && DEFAULT_NAME === $timerReport->getName()) {
//                $this->elapsed = (string)$timerReport;
                $r .= $timerReport;
            } else {
                $r .= $timerReport;
            }
        }
        return $r;
    }
}
