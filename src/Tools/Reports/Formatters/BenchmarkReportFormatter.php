<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use function AlecRabbit\typeOf;

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

    /**
     * @param array $arguments
     * @return array
     */
    protected function extractArguments(array $arguments): array
    {
        $types = [];
        if (!empty($arguments)) {
            foreach ($arguments as $argument) {
                $types[] = typeOf($argument);
            }
        }
        return $types;
    }

    /**
     * @param float $average
     * @return string
     */
    protected function average(float $average): string
    {
        return str_pad(
            Pretty::time($average),
            8,
            ' ',
            STR_PAD_LEFT
        );
    }

    /**
     * @param float $relative
     * @return string
     */
    protected function relativePercent(float $relative): string
    {
        return str_pad(
            Pretty::percent($relative),
            7,
            ' ',
            STR_PAD_LEFT
        );
    }
}
