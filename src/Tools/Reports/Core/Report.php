<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 20:57
 */

namespace AlecRabbit\Tools\Reports\Core;

use AlecRabbit\Tools\Reports\Contracts\OldReportInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Factory;
use AlecRabbit\Tools\Reports\Formatters\Contracts\OldFormatter;

abstract class Report implements ReportInterface
{
    /** @var ReportInterface */
    protected $report;

    public function process(ReportInterface $report): string
    {
        // TODO: Implement process() method.
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return
            $this->process($this->report);
    }
}
