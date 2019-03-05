<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Contracts\StringConstants;
use AlecRabbit\Tools\Reports\Contracts\OldReportInterface;
use AlecRabbit\Tools\Reports\Formatters\Contracts\OldFormatter;

abstract class OldReportFormatter implements OldFormatter, StringConstants
{
    /** @var OldReportInterface */
    protected $report;

    /**
     * Formatter constructor.
     * @param OldReportInterface $report
     */
    public function __construct(OldReportInterface $report)
    {
        $this->report = $report;
    }

    /** {@inheritdoc} */
    abstract public function process(): string;
}
