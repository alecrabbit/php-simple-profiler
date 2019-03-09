<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;

abstract class ReportFormatter implements FormatterInterface, Strings
{
    /** {@inheritdoc} */
    abstract public function process(ReportInterface $report): string;
}
