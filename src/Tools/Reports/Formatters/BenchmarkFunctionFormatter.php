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
        $r = '';
        $withException = '';
        /** @var BenchmarkFunction $function */
        $function = $this->function;
        $br = $function->getBenchmarkRelative();
        $types = $this->extractArguments($function->getArgs());
        if ($br) {
            $relative = $br->getRelative();
            $average = $br->getAverage();
            $rank = $br->getRank();
            $r .=
                sprintf(
                    '%s. %s (%s) %s(%s) %s %s',
                    (string)$rank,
                    $this->average($average),
                    $this->relativePercent($relative),
                    $function->humanReadableName(),
                    implode(', ', $types),
                    $function->comment(),
                    PHP_EOL
                );
            $result = $function->getResult();
            $r .=
                sprintf(
                    '%s(%s) %s',
                    typeOf($result),
                    var_export($result, true),
                    PHP_EOL
                );
        } elseif ($e = $function->getException()) {
            $withException .= sprintf(
                '%s(%s) %s %s %s',
                $function->humanReadableName(),
                implode(', ', $types),
                $function->comment(),
                $e->getMessage(),
                PHP_EOL
            );
        } else {
            // @codeCoverageIgnoreStart
            throw new \RuntimeException('BenchmarkFunction has no BenchmarkRelative' .
                ' nor Exception object. Was it executed?');
            // @codeCoverageIgnoreEnd
        }
        return
            $r . (empty($withException) ? PHP_EOL : 'Exceptions:' . PHP_EOL . $withException);
    }

    /**
     * @param array $arguments
     * @return array
     */
    protected function extractArguments(array $arguments): array
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
}
