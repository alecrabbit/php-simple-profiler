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
    /** @var bool */
    protected $benchmarkedAny;
    /** @var bool */
    protected $benchmarkedMoreThanOne;

    /** {@inheritdoc} */
    public function process(ReportInterface $report): string
    {
        if ($report instanceof BenchmarkReport) {
            return $this->build($report);
        }
        $this->wrongReportType(BenchmarkReport::class, $report);
        // @codeCoverageIgnoreStart
        return ''; // never executes
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param BenchmarkReport $report
     * @return string
     */
    protected function build(BenchmarkReport $report): string
    {
        $this->report = $report;
        $str = 'Results:' . PHP_EOL;
        $this->computeVariables();
        if ($this->benchmarkedAny) {
            $str .= self::BENCHMARK . PHP_EOL;
        }
//        dump($this->equalReturns && $this->report->isNotShowReturns());
//        dump($this->equalReturns || $this->report->isNotShowReturns());
        /** @var BenchmarkFunction $function */
        foreach ($report->getFunctions() as $name => $function) {
            $str .=
                Factory::getBenchmarkFunctionFormatter()
                    ->noReturnIf($this->equalReturns || $this->report->isNotShowReturns())
                    ->process($function);
        }
        return
            sprintf(
                '%s%s%s%s%s',
                $str,
                $this->strEqualReturns(),
                $this->countersStatistics($this->added, $this->benchmarked),
                $report->getMemoryUsageReport(),
                PHP_EOL
            );
    }

    protected function computeVariables(): void
    {
        $this->added = $this->report->getAdded()->getValue();
        $this->benchmarked = $this->report->getBenchmarked()->getValue();
        $this->benchmarkedAny =
            $this->added !== $this->added - $this->benchmarked;
        $this->benchmarkedMoreThanOne =
            $this->benchmarked > 1;
//        dump('$this->benchmarkedMoreThanOne', $this->benchmarkedMoreThanOne);
        $this->equalReturns = array_is_homogeneous($this->reportFunctionsReturns());
    }

    /**
     * @return array
     */
    protected function reportFunctionsReturns(): array
    {
        $returns = [];
        /** @var BenchmarkFunction $function */
        foreach ($this->report->getFunctions() as $name => $function) {
            $returns[] = $this->lastReturn = $function->getReturn();
        }
        return $returns;
    }

    /**
     * @return string
     */
    protected function strEqualReturns(): string
    {
        return $this->benchmarkedAny ? $this->allReturnsAreEqual() : '';
    }

    private function allReturnsAreEqual(): string
    {
        $str = '';
        if ($this->equalReturns) {
            $aRAE = $this->benchmarkedMoreThanOne ? 'All returns are equal' : '' ;
            $dLM = $this->benchmarkedMoreThanOne ? '.' : '' ;
            $str .=
                sprintf(
                    '%s%s%s',
                    $aRAE,
                    $this->benchmarkedMoreThanOne && $this->report->isShowReturns() ?
                        ':' . PHP_EOL . BenchmarkFunctionFormatter::returnToString($this->lastReturn) :
                        $dLM,
                    PHP_EOL
                );
        }
        return $str;
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
