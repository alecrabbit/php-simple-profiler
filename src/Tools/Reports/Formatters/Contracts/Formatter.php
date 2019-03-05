<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:23
 */

namespace AlecRabbit\Tools\Reports\Formatters\Contracts;

use AlecRabbit\Tools\Reports\Contracts\OldReportInterface;

interface Formatter
{
    /**
     * @param OldReportInterface $report
     * @return string
     */
    public function process(OldReportInterface $report): string;
}
