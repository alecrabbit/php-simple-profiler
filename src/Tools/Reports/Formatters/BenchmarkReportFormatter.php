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
        $str = 'Results:' . PHP_EOL;
        $added = $this->added($report);
        $benchmarked = $this->benchmarked($report);
        $benchmarkedMoreThanOne = $this->benchmarkedMoreThanOne($added, $benchmarked);
        if ($benchmarkedMoreThanOne) {
            $str .= self::BENCHMARK . PHP_EOL;
        }
        $equalReturns = $this->checkReturns($report);
        /** @var BenchmarkFunction $function */
        foreach ($report->getFunctions() as $name => $function) {
            $str .=
                Factory::getBenchmarkFunctionFormatter()
                    ->noReturnIf($equalReturns)
                    ->process($function);
        }
        return
            sprintf(
                '%s%s%s%s%s',
                $str,
                $benchmarkedMoreThanOne ? $this->allReturnsAreEqual($equalReturns) : '',
                $this->countersStatistics($added, $benchmarked),
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
     * @param int $added
     * @param int $benchmarked
     * @return bool
     */
    private function benchmarkedMoreThanOne(int $added, int $benchmarked): bool
    {
//        return $added !== $i = $added - $benchmarked;
        return ($added !== $i = $added - $benchmarked) && 1 < $i;
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
