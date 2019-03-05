<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Contracts\StringConstants;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Formatters\Contracts\OldFormatter;

abstract class OldReportFormatter implements OldFormatter, StringConstants
{
    /** @var ReportInterface */
    protected $report;

    /**
     * Formatter constructor.
     * @param ReportInterface $report
     */
    public function __construct(ReportInterface $report)
    {
        $this->report = $report;
    }

    /** {@inheritdoc} */
    abstract public function process(): string;
}
