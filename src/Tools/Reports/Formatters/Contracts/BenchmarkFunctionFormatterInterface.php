<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters\Contracts;

use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;

interface BenchmarkFunctionFormatterInterface
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
    public static function returnToString($executionReturn): string;
}
