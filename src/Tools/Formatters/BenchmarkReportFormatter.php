<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Formatters;

use AlecRabbit\Tools\Factory;
use AlecRabbit\Tools\Formattable;
use AlecRabbit\Tools\Formatters\Contracts\BenchmarkReportFormatterInterface;
use AlecRabbit\Tools\Formatters\Core\ReportFormatter;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use function AlecRabbit\array_is_homogeneous;

/**
 * @psalm-suppress MissingConstructor
 *
 * Class BenchmarkReportFormatter
 * @package AlecRabbit\Tools\Reports\Formatters
 */
class BenchmarkReportFormatter extends ReportFormatter implements BenchmarkReportFormatterInterface
{
    protected const ALL_RETURNS_ARE_EQUAL = 'All returns are equal';

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
    protected $anyExceptions;
    /** @var bool */
    protected $benchmarkedMoreThanOne;

    /** {@inheritdoc} */
    public function process(Formattable $formattable): string
    {
        if ($formattable instanceof BenchmarkReport) {
            return $this->build($formattable);
        }
        $this->wrongFormattableType(BenchmarkReport::class, $formattable);
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
        $str = static::RESULTS . PHP_EOL;
        $this->computeVariables();
        if ($this->benchmarkedAny) {
            $str .= static::BENCHMARK . PHP_EOL;
        }
        if ($this->anyExceptions) {
            $exceptions = static::EXCEPTIONS . PHP_EOL;
        } else {
            $exceptions = '';
        }

        /** @var BenchmarkFunction $function */
        foreach ($report->getFunctions() as $name => $function) {
            $tmp =
                Factory::getBenchmarkFunctionFormatter()
                    ->noReturnIf($this->equalReturns || $this->report->isNotShowReturns())
                    ->process($function);
            if (null === $function->getException()) {
                $str .= $tmp;
            } else {
                $exceptions .= $tmp;
            }
        }
        return
            sprintf(
                '%s%s%s%s%s',
                $str,
                $this->strEqualReturns(),
                $exceptions,
                $this->countersStatistics(),
                PHP_EOL
            );
//        return
//            sprintf(
//                '%s%s%s%s%s',
//                $str,
//                $this->strEqualReturns(),
//                $this->countersStatistics(),
//                $report->getMemoryUsageReport(),
//                PHP_EOL
//            );
    }

    protected function computeVariables(): void
    {
        $this->added = $this->report->getAdded()->getValue();
        $this->benchmarked = $this->report->getBenchmarked()->getValue();
        $this->benchmarkedAny =
            $this->added !== $this->added - $this->benchmarked;
        $this->anyExceptions =
            $this->added !== $this->benchmarked;
        $this->benchmarkedMoreThanOne =
            $this->benchmarked > 1;
        $this->equalReturns = $this->equalReturns();
    }

    /**
     * @return bool
     */
    protected function equalReturns(): bool
    {
        return
            array_is_homogeneous($this->reportFunctionsReturns());
    }

    /**
     * @return array
     */
    protected function reportFunctionsReturns(): array
    {
        $returns = [];
        /** @var BenchmarkFunction $function */
        foreach ($this->report->getFunctions() as $name => $function) {
            if (null !== $function->getBenchmarkRelative()) {
                $returns[] = $this->lastReturn = $function->getReturn();
            }
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
            $aRAE = $this->benchmarkedMoreThanOne ? static::ALL_RETURNS_ARE_EQUAL : '';
            $dLM = $this->benchmarkedMoreThanOne ? '.' : '';
            $str .=
                sprintf(
                    '%s%s%s',
                    $aRAE,
                    $this->benchmarkedMoreThanOne && $this->report->isShowReturns() ?
                        ':' . PHP_EOL . Factory::getBenchmarkFunctionFormatter()->returnToString($this->lastReturn) :
                        $dLM,
                    PHP_EOL
                );
        }
        return $str;
    }

    /**
     * @return string
     */
    private function countersStatistics(): string
    {
        if ($this->added === $this->benchmarked) {
            return sprintf(
                '%s: %s %s',
                static::BENCHMARKED,
                $this->benchmarked,
                PHP_EOL
            );
        }

        return
            sprintf(
                '%s: %s %s: %s %s %s',
                static::ADDED,
                $this->added,
                static::BENCHMARKED,
                $this->benchmarked,
                $this->countedExceptions(),
                PHP_EOL
            );
    }

    /**
     * @return string
     */
    protected function countedExceptions(): string
    {
        if (0 !== $exceptions = $this->added - $this->benchmarked) {
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
