<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:25
 */
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Formatters\Contracts\ReportFormatter;

abstract class Formatter implements ReportFormatter
{
    /** @var ReportInterface */
    protected $report;

    public function __construct(ReportInterface $report)
    {
        $this->report = $report;
    }

    abstract public function getString(): string;
}