<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use function AlecRabbit\typeOf;

abstract class ReportFormatter extends Formatter
{
    /** {@inheritdoc} */
    abstract public function process(ReportInterface $report): string;

    /**
     * @param string $expected
     * @param ReportInterface $report
     * @throws \RuntimeException
     */
    protected function wrongReportType(string $expected, ReportInterface $report): void
    {
        throw new \RuntimeException(
            'Instance of [' . $expected . '] expected, [' . typeOf($report) . '] given.'
        );
    }
}
