<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Formatters\Contracts;

use AlecRabbit\Tools\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Formatters\Contracts\FormatterInterface;

interface BenchmarkFunctionFormatterInterface extends FormatterInterface
{
    /**
     * @param bool $equalReturns
     * @return BenchmarkFunctionFormatter
     */
    public function noReturnIf(bool $equalReturns = false): BenchmarkFunctionFormatter;

    /**
     * @param mixed $executionReturn
     * @return string
     */
    public function returnToString($executionReturn): string;
}
