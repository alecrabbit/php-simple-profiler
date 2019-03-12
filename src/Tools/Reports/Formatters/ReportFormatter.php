<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use function AlecRabbit\typeOf;

abstract class ReportFormatter implements FormatterInterface, Strings
{
    /** {@inheritdoc} */
    abstract public function process(ReportInterface $report): string;

    /**
     * @param string $expected
     * @param ReportInterface $report
     * @throws \RuntimeException
     */
    protected function wrongReport(string $expected, ReportInterface $report): void
    {
        throw new \RuntimeException(
            'Instance of [' . $expected . '] expected, [' . typeOf($report) . '] given'
        );
    }
}
