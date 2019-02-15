<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\BenchmarkReport;

class BenchmarkReportFormatter extends ReportFormatter
{
    /** @var BenchmarkReport */
    protected $report;

    /**
     * {@inheritdoc}
     */
    public function getString(): string
    {
        $r = 'Benchmark:' . PHP_EOL;
        /** @var BenchmarkFunction $function */
        foreach ($this->report->getFunctions() as $name => $function) {
            $r .= (new  BenchmarkFunctionFormatter($function))->getString();
        }
        return
            $r . PHP_EOL .
            $this->report->getMemoryUsageReport() . PHP_EOL .
            $this->report->getProfiler()->getReport();
    }
}
