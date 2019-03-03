<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use function AlecRabbit\array_is_homogeneous;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use AlecRabbit\Tools\Reports\Factory;

class BenchmarkReportFormatter extends ReportFormatter
{
    /** @var BenchmarkReport */
    protected $report;
    /** @var mixed */
    protected $lastReturn;

    /**
     * {@inheritdoc}
     */
    public function getString(): string
    {
        $str = self::BENCHMARK . PHP_EOL;
        $equalReturns = $this->checkReturns();
        /** @var BenchmarkFunction $function */
        foreach ($this->report->getFunctions() as $name => $function) {
            $str .=
                Factory::getBenchmarkFunctionFormatter($function)
                    ->noResultsIf($equalReturns)
                    ->getString();
//            $str .=
//                (new BenchmarkFunctionFormatter($function))
//                    ->noResultsIf($equalReturns)
//                    ->getString();
        }
        return
            sprintf(
                '%s%s%s%s%s',
                $str,
                $this->allReturnsAreEqual($equalReturns),
                $this->countersStatistics(),
                $this->report->getMemoryUsageReport(),
                PHP_EOL
            );
    }

    /**
     * @return bool
     */
    protected function checkReturns(): bool
    {
        return
            array_is_homogeneous($this->functionsReturns());
    }

    /**
     * @return array
     */
    private function functionsReturns(): array
    {
        $returns = [];
        /** @var BenchmarkFunction $function */
        foreach ($this->report->getFunctions() as $name => $function) {
            $returns[] = $this->lastReturn = $function->getReturn();
        }
        return $returns;
    }

    private function allReturnsAreEqual(bool $equalReturns): string
    {
        if (!$equalReturns) {
            return '';
        }
        return
            sprintf(
                '%s %s%s %s',
                'All returns are equal:',
                PHP_EOL,
                BenchmarkFunctionFormatter::returnToString($this->lastReturn),
                PHP_EOL
            );
    }

    /**
     * @return string
     */
    private function countersStatistics(): string
    {
        $added =
            $this->report->getProfiler()
                ->counter(static::ADDED)->getValue();
        $benchmarked =
            $this->report->getProfiler()
                ->counter(static::BENCHMARKED)->getValue();
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

    /**
     * @param int $added
     * @param int $benchmarked
     * @return string
     */
    private function countedExceptions(int $added, int $benchmarked): string
    {
        if (0 !== $exceptions = $added - $benchmarked) {
            return
                sprintf(
                    '%s %s',
                    static::EXCEPTIONS,
                    $exceptions
                );
        }
        // @codeCoverageIgnoreStart
        return '';
        // @codeCoverageIgnoreEnd
    }
}
