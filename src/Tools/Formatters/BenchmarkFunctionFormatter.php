<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Formatters;

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Tools\Formattable;
use AlecRabbit\Tools\Formatters\Contracts\BenchmarkFunctionFormatterInterface;
use AlecRabbit\Tools\Formatters\Core\Formatter;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use SebastianBergmann\Exporter\Exporter;
use function AlecRabbit\typeOf;

class BenchmarkFunctionFormatter extends Formatter implements BenchmarkFunctionFormatterInterface
{
    /** @var null|Exporter */
    protected static $exporter;

    /** @var bool */
    protected $equalReturns = false;

    /** {@inheritdoc} */
    public function resetEqualReturns(): BenchmarkFunctionFormatter
    {
        return
            $this->noReturnIf();
    }

    /** {@inheritdoc} */
    public function noReturnIf(bool $equalReturns = false): BenchmarkFunctionFormatter
    {
        $this->equalReturns = $equalReturns;
        return $this;
    }

    /** {@inheritdoc} */
    public function format(Formattable $function): string
    {
        if ($function instanceof BenchmarkFunction) {
            return
                $this->formatBenchmarkRelative($function) .
                (empty($exception = $this->formatException($function)) ?
                    PHP_EOL :
                    /*static::EXCEPTIONS .*/ $exception . PHP_EOL);
        }
        $this->wrongFormattableType(BenchmarkFunction::class, $function);
        // @codeCoverageIgnoreStart
        return ''; // never executes
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param BenchmarkFunction $function
     * @return string
     */
    protected function formatBenchmarkRelative(BenchmarkFunction $function): string
    {
        if ($function->getBenchmarkRelative()) {
            $executionReturn = $function->getReturn();
            if ($this->equalReturns || $function->isNotShowReturns()) {
                return $this->preformatFunction($function);
            }
            return
                sprintf(
                    '%s %s %s %s',
                    $this->preformatFunction($function),
                    PHP_EOL,
                    $this->returnToString($executionReturn),
                    PHP_EOL
                );
        }
        return '';
    }

    /**
     * @param array $arguments
     * @return array
     */
    protected function extractArgumentsTypes(array $arguments): array
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
     * @param BenchmarkFunction $function
     *
     * @return string
     */
    protected function preformatFunction(
        BenchmarkFunction $function
    ): string {
        $argumentsTypes = $this->extractArgumentsTypes($function->getArgs());
        if ($br = $function->getBenchmarkRelative()) {
            return
                sprintf(
                    '%s. %s (%s) %s(%s) %s',
                    (string)$br->getRank(),
                    $this->average($br->getAverage()),
                    $this->relativePercent($br->getRelative()),
                    $function->humanReadableName(),
                    implode(', ', $argumentsTypes),
                    $function->comment()
                );
        }
        return '';
    }

    /**
     * @param float $average
     * @return string
     */
    protected function average(float $average): string
    {
        return
            str_pad(
                Pretty::time($average),
                8,
                ' ',
                STR_PAD_LEFT
            );
    }

    /**
     * @param float $relative
     * @param string $prefix
     * @return string
     */
    protected function relativePercent(float $relative, string $prefix = ' '): string
    {
        return
            str_pad(
                $prefix . Pretty::percent($relative),
                9,
                ' ',
                STR_PAD_LEFT
            );
    }

    /** {@inheritdoc} */
    public function returnToString($executionReturn): string
    {
        $type = typeOf($executionReturn);
        $str = static::getExporter()->export($executionReturn);
        return
            'array' === $type ?
                $str :
                sprintf(
                    '%s(%s)',
                    $type,
                    $str
                );
    }

    /**
     * @return Exporter
     */
    protected static function getExporter(): Exporter
    {
        if (null === static::$exporter) {
            static::$exporter = new Exporter();
        }
        return static::$exporter;
    }

    /**
     * @param BenchmarkFunction $function
     * @return string
     */
    protected function formatException(BenchmarkFunction $function): string
    {

        if ($e = $function->getException()) {
            $argumentsTypes = $this->extractArgumentsTypes($function->getArgs());

            return
                sprintf(
                    '%s(%s) %s [%s: %s] %s',
                    $function->humanReadableName(),
                    implode(', ', $argumentsTypes),
                    $function->comment(),
                    typeOf($e),
                    $e->getMessage(),
                    PHP_EOL
                );
        }

        return '';
    }
}
