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
        $str = self::BENCHMARK . PHP_EOL;
        /** @var BenchmarkFunction $function */
        foreach ($this->report->getFunctions() as $name => $function) {
            $str .= (new BenchmarkFunctionFormatter($function))->getString();
        }
        return
            sprintf(
                '%s %s%s %s%s',
                $str,
                PHP_EOL,
                $this->countersStatistics(),
                PHP_EOL,
                $this->report->getMemoryUsageReport()
            );
    }

    private function countersStatistics(): string
    {
        $addedCounter = $this->report->getProfiler()->counter(static::ADDED);
        $benchmarkedCounter = $this->report->getProfiler()->counter(static::BENCHMARKED);

        return
            sprintf(
                '%s: %s %s: %s',
                self::ADDED,
                $addedCounter->getValue(),
                self::BENCHMARKED,
                $benchmarkedCounter->getValue()
            );
    }
}
