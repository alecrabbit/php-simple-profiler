<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Formatters;

use AlecRabbit\Tools\Formattable;
use AlecRabbit\Tools\Formatters\Core\ReportFormatter;
use AlecRabbit\Tools\Reports\AbstractCounterReport;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\TimerReport;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class ProfilerReportFormatter extends ReportFormatter
{
    /** @var string */
    private $elapsed = '';

    /** {@inheritdoc} */
    public function format(Formattable $formattable): string
    {
        if ($formattable instanceof ProfilerReport) {
            return
                sprintf(
                    '%s%s%s',
                    $this->countersStrings($formattable),
                    $this->timersStrings($formattable),
                    $this->elapsed
                );
        }
        $this->wrongFormattableType(ProfilerReport::class, $formattable);
        // @codeCoverageIgnoreStart
        return ''; // never executes
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
