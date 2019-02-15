<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\Formatters\Contracts\Formatter;
use function AlecRabbit\typeOf;

class BenchmarkFunctionFormatter implements Formatter
{
    /** @var BenchmarkFunction */
    protected $function;

    public function __construct(BenchmarkFunction $function)
    {
        $this->function = $function;
    }

    /**
     * {@inheritdoc}
     */
    public function getString(): string
    {
        return
            $this->formatBenchmarkRelative() .
            (empty($exception = $this->formatException()) ?
                PHP_EOL :
                'Exceptions:' . PHP_EOL . $exception);
    }

    /**
     * @return string
     */
    protected function formatBenchmarkRelative(): string
    {
        $function = $this->function;
        if ($br = $function->getBenchmarkRelative()) {
            $argumentsTypes = $this->extractArgumentsTypes($function->getArgs());
            $executionReturn = $function->getReturn();

            return
                sprintf(
                    '%s. %s (%s) %s(%s) %s %s %s(%s) %s',
                    (string)$br->getRank(),
                    $this->average($br->getAverage()),
                    $this->relativePercent($br->getRelative()),
                    $function->humanReadableName(),
                    implode(', ', $argumentsTypes),
                    $function->comment(),
                    PHP_EOL,
                    typeOf($executionReturn),
                    var_export($executionReturn, true),
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

    /**
     * @return string
     */
    protected function formatException(): string
    {

        if ($e = $this->function->getException()) {
            $argumentsTypes = $this->extractArgumentsTypes($this->function->getArgs());

            return
                sprintf(
                    '%s(%s) %s [%s] %s',
                    $this->function->humanReadableName(),
                    implode(', ', $argumentsTypes),
                    $this->function->comment(),
                    $e->getMessage(),
                    PHP_EOL
                );
        }

        return '';
    }
}
