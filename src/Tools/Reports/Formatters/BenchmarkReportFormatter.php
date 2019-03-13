<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Factory;
use AlecRabbit\Tools\Reports\Formatters\Contracts\BenchmarkReportFormatterInterface;
use function AlecRabbit\array_is_homogeneous;

class BenchmarkReportFormatter extends ReportFormatter implements BenchmarkReportFormatterInterface
{
    /** @var BenchmarkReport */
    protected $report;
    /** @var mixed */
    protected $lastReturn;
    /** @var int */
    protected $added;
    /** @var int */
    protected $benchmarked;
    /** @var bool */
    protected $equalReturns;

    /** {@inheritdoc} */
    public function process(ReportInterface $report): string
    {
        if ($report instanceof BenchmarkReport) {
            return $this->build($report);
        }
        $this->wrongReport(BenchmarkReport::class, $report);
        return ''; // never executes
    }

    /**
     * @param BenchmarkReport $report
     * @return string
     */
    protected function build(BenchmarkReport $report): string
    {
        $this->report = $report;
        $str = 'Results:' . PHP_EOL;
        $this->added = $this->added($report);
        $this->benchmarked = $this->benchmarked($report);
        $benchmarkedAny = $this->benchmarkedAny();
        $this->equalReturns = $this->checkReturns($report);
        if ($benchmarkedAny) {
            $str .= self::BENCHMARK . PHP_EOL;
        }
        /** @var BenchmarkFunction $function */
        foreach ($report->getFunctions() as $name => $function) {
            $str .=
                Factory::getBenchmarkFunctionFormatter()
                    ->noReturnIf($this->equalReturns)
                    ->process($function);
        }
        return
            sprintf(
                '%s%s%s%s%s',
                $str,
                $this->strEqualReturns($report, $benchmarkedAny, $this->equalReturns),
                $this->countersStatistics($this->added, $this->benchmarked),
                $report->getMemoryUsageReport(),
                PHP_EOL
            );
    }

    /**
     * @param BenchmarkReport $report
     * @return int
     */
    private function added(BenchmarkReport $report): int
    {
        return
            $report->getAdded()->getValue();
    }

    /**
     * @param BenchmarkReport $report
     * @return int
     */
    private function benchmarked(BenchmarkReport $report): int
    {
        return
            $report->getBenchmarked()->getValue();
    }

    /**
     * @return bool
     */
    private function benchmarkedAny(): bool
    {
        return $this->added !== $this->added - $this->benchmarked;
    }

    /**
     * @param BenchmarkReport $report
     * @return bool
     */
    protected function checkReturns(BenchmarkReport $report): bool
    {
        return
            array_is_homogeneous($this->functionsReturns($report));
    }

    /**
     * @param BenchmarkReport $report
     * @return array
     */
    private function functionsReturns(BenchmarkReport $report): array
    {
        $returns = [];
        /** @var BenchmarkFunction $function */
        foreach ($report->getFunctions() as $name => $function) {
            $returns[] = $this->lastReturn = $function->getReturn();
        }
        return $returns;
    }

    /**
     * @param BenchmarkReport $report
     * @param bool $benchmarkedAny
     * @param bool $equalReturns
     * @return string
     */
    protected function strEqualReturns(BenchmarkReport $report, bool $benchmarkedAny, bool $equalReturns): string
    {
        return $benchmarkedAny ? $this->allReturnsAreEqual($equalReturns) : '';
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
