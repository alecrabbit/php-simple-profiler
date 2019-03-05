<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Contracts\StringConstants;
use AlecRabbit\Tools\Reports\Contracts\OldReportInterface;
use AlecRabbit\Tools\Reports\Formatters\Contracts\Formatter;

abstract class ReportFormatter implements Formatter, StringConstants
{
    /** {@inheritdoc} */
    abstract public function process(OldReportInterface $report): string;
}
