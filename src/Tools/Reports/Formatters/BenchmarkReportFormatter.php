<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use AlecRabbit\Tools\Reports\Factory;
use function AlecRabbit\array_is_homogeneous;

class BenchmarkReportFormatter extends ReportFormatter
{
    /** @var BenchmarkReport */
    protected $report;
    /** @var mixed */
    protected $lastReturn;

    /**
     * {@inheritdoc}
     */
    public function process(): string
    {
        $str = 'Results:' . PHP_EOL;
        $added = $this->added();
        $benchmarked = $this->benchmarked();
        $benchmarkedAny = $this->benchmarkedAny($added, $benchmarked);
        if ($benchmarkedAny) {
            $str .= self::BENCHMARK . PHP_EOL;
        }
        $equalReturns = $this->checkReturns();
        /** @var BenchmarkFunction $function */
        foreach ($this->report->getFunctions() as $name => $function) {
            $str .=
                Factory::getBenchmarkFunctionFormatter($function)
                    ->noResultsIf($equalReturns)
                    ->process();
        }
        return
            sprintf(
                '%s%s%s%s%s',
                $str,
                $benchmarkedAny ? $this->allReturnsAreEqual($equalReturns) : '',
                $this->countersStatistics($added, $benchmarked),
                $this->report->getMemoryUsageReport(),
                PHP_EOL
            );
    }

    /**
     * @return int
     */
    private function added(): int
    {
        return
            $this->report->getProfiler()
                ->counter(static::ADDED)->getValue();
    }

    /**
     * @return int
     */
    private function benchmarked(): int
    {
        return
            $this->report->getProfiler()
                ->counter(static::BENCHMARKED)->getValue();
    }

    /**
     * @param int $added
     * @param int $benchmarked
     * @return bool
     */
    private function benchmarkedAny(int $added, int $benchmarked): bool
    {
        return $added !== $added - $benchmarked;
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
        if ($equalReturns) {
            return
                sprintf(
                    '%s %s%s %s',
                    'All returns are equal:',
                    PHP_EOL,
                    BenchmarkFunctionFormatter::returnToString($this->lastReturn),
                    PHP_EOL
                );
        }
        return '';
    }

    /**
     * @param int $added
     * @param int $benchmarked
     * @return string
     */
    private function countersStatistics(int $added, int $benchmarked): string
    {
        if ($added === $benchmarked) {
            return sprintf(
                '%s: %s %s',
                static::BENCHMARKED,
                $benchmarked,
                PHP_EOL
            );
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
