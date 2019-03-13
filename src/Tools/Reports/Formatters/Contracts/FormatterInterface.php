<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters\Contracts;

use AlecRabbit\Tools\Reports\Contracts\ReportInterface;

interface FormatterInterface
{
    /**
     * @param ReportInterface $report
     * @return string
     */
    public function process(ReportInterface $report): string;
}
