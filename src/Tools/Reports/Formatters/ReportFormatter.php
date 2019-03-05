<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Contracts\StringConstants;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Formatters\Contracts\OldFormatter;

abstract class ReportFormatter implements OldFormatter, StringConstants
{
    /** {@inheritdoc} */
    abstract public function process(ReportInterface $report): string;
}
