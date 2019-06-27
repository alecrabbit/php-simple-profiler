<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Formatters;

use AlecRabbit\Formatters\Core\AbstractFormatter;
use AlecRabbit\Reports\Core\Formattable;
use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Formatters\Contracts\BenchmarkReportFormatterInterface;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use Illuminate\Contracts\Container\BindingResolutionException;
use function AlecRabbit\array_is_homogeneous;
use function AlecRabbit\container;

/**
 * @psalm-suppress MissingConstructor
 *
 * Class BenchmarkReportFormatter
 * @package AlecRabbit\Tools\Reports\Formatters
 */
class BenchmarkReportFormatter extends AbstractFormatter implements BenchmarkReportFormatterInterface, Strings
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

    /**
     * {@inheritdoc}
     * @throws BindingResolutionException
     */
    public function format(Formattable $formattable): string
    {
        if ($formattable instanceof BenchmarkReport) {
            return $this->build($formattable);
        }
        return
            $this->errorMessage($formattable, BenchmarkReport::class);
    }

    /**
     * @param BenchmarkReport $report
     * @return string
     * @throws BindingResolutionException
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
                container()->make(BenchmarkFunctionFormatter::class)
                    ->noReturnIf($this->equalReturns || $this->report->isNotShowReturns())
                    ->format($function);
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
     * @throws BindingResolutionException
     */
    protected function strEqualReturns(): string
    {
        return $this->benchmarkedAny ? $this->allReturnsAreEqual() : '';
    }

    /**
     * @return string
     * @throws BindingResolutionException
     */
    private function allReturnsAreEqual(): string
    {
        $str = '';
        if ($this->equalReturns) {
            $aRAE = $this->benchmarkedMoreThanOne ? static::ALL_RETURNS_ARE_EQUAL : '';
            $dLM = $this->benchmarkedMoreThanOne ? '.' : '';
            $formattedLastReturn = container()
                ->make(BenchmarkFunctionFormatter::class)
                ->returnToString($this->lastReturn);
            $str .=
                sprintf(
                    '%s%s%s',
                    $aRAE,
                    $this->benchmarkedMoreThanOne && $this->report->isShowReturns() ?
                        ':' . PHP_EOL . $formattedLastReturn :
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
