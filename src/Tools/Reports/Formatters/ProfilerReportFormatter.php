<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:22
 */
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Reports\ProfilerReport;

class ProfilerReportFormatter extends Formatter
{
    /** @var ProfilerReport */
    protected $report;

    public function setStyles(): void
    {
    }

    public function getString(): string
    {
        $r = '';
        foreach ($this->report->getReports() as $reports) {
            foreach ($reports as $report) {
                $r .= $report;
            }
        }
        return $r;
    }
}
