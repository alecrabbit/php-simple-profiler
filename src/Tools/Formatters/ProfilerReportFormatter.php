<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Formatters;

use AlecRabbit\Formatters\Core\AbstractFormatter;
use AlecRabbit\Reports\Core\AbstractCounterReport;
use AlecRabbit\Reports\Core\Formattable;
use AlecRabbit\Reports\TimerReport;
use AlecRabbit\Tools\Reports\ProfilerReport;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class ProfilerReportFormatter extends AbstractFormatter
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
        return
            $this->errorMessage($formattable, ProfilerReport::class);
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
