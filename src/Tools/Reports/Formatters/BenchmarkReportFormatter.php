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
                '%s%s%s%s',
                $str,
                $this->countersStatistics(),
                $this->report->getMemoryUsageReport(),
                PHP_EOL
            );
    }

    private function countersStatistics(): string
    {
        $added = $this->report->getProfiler()->counter(static::ADDED)->getValue();
        $benchmarked = $this->report->getProfiler()->counter(static::BENCHMARKED)->getValue();
        if ($added === $benchmarked) {
            return '';
        }

        return
            sprintf(
                '%s: %s %s: %s %s %s',
                static::ADDED,
                $added,
                static::BENCHMARKED,
                $benchmarked,
                $this->countedExceptions($added, $benchmarked),
                PHP_EOL
            );
    }

    private function countedExceptions($added, $benchmarked): string
    {
        if (0 !== $exceptions = $added - $benchmarked) {
            return
                sprintf(
                    '%s %s',
                    static::EXCEPTIONS,
                    $exceptions
                );
        }
        return '';
    }
}
