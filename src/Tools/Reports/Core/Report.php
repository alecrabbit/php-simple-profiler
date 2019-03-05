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
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\Reports\Formatters\Contracts\OldFormatter;

abstract class Report implements ReportInterface
{
    /** @var ReportInterface */
    protected $report;

    abstract protected static function getFormatter(): FormatterInterface;

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return
            static::getFormatter()->process($this->report);
    }
}
