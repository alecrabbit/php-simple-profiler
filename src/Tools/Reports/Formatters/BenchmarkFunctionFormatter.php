<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Tools\Formattable;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\Formatters\Contracts\BenchmarkFunctionFormatterInterface;
use SebastianBergmann\Exporter\Exporter;
use function AlecRabbit\typeOf;

class BenchmarkFunctionFormatter extends Formatter implements BenchmarkFunctionFormatterInterface
{
    /** @var null|Exporter */
    protected static $exporter;

    /** @var bool */
    protected $equalReturns = false;

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
    public function process(Formattable $function): string
    {
        if ($function instanceof BenchmarkFunction) {
            return
                $this->formatBenchmarkRelative($function) .
                (empty($exception = $this->formatException($function)) ?
                    PHP_EOL :
                    static::EXCEPTIONS . PHP_EOL . $exception);
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
        if ($br = $function->getBenchmarkRelative()) {
            $argumentsTypes = $this->extractArgumentsTypes($function->getArgs());
            $executionReturn = $function->getReturn();
            if ($this->equalReturns || $function->isNotShowReturns()) {
                return $this->preformatFunction($br, $function, $argumentsTypes);
            }
            return
                sprintf(
                    '%s %s %s %s',
                    $this->preformatFunction($br, $function, $argumentsTypes),
                    PHP_EOL,
                    static::returnToString($executionReturn),
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
     * @param BenchmarkRelative $br
     * @param BenchmarkFunction $function
     * @param array $argumentsTypes
     * @return string
     * todo rename method
     */
    protected function preformatFunction(
        BenchmarkRelative $br,
        BenchmarkFunction $function,
        array $argumentsTypes
    ): string {
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

    /** {@inheritdoc} */
    public static function returnToString($executionReturn): string
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
