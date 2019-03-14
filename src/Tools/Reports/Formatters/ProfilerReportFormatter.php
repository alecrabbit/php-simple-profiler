<?php
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\AbstractCounter;
use AlecRabbit\Tools\Reports\AbstractCounterReport;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\SimpleCounterReport;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\TimerReport;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;
use function AlecRabbit\typeOf;

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
                    '%s%s%s',
                    $this->countersStrings($report),
                    $this->timersStrings($report),
                    $this->elapsed
                );
        }
        $this->wrongReportType(ProfilerReport::class, $report);
        // @codeCoverageIgnoreStart
        return '';
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param ProfilerReport $report
     * @return string
     */
    protected function countersStrings(ProfilerReport $report): string
    {
        $r = '';
        foreach ($report->getCountersReports() as $counterReport) {
            if ($counterReport instanceof AbstractCounterReport && DEFAULT_NAME === $counterReport->getName()) {
                $r .= $counterReport->isStarted() ? $counterReport : '';
            } else {
                $r .= $counterReport;
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
                $this->elapsed = (string)$timerReport;
//                $r .= $timerReport;
            } else {
                $r .= $timerReport;
            }
        }
        return $r;
    }
}
