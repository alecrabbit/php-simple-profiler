<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:26
 */

namespace AlecRabbit\Tools\Reports\Contracts;

interface ReportInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @param ReportInterface $report
     * @return string
     */
    public function process(ReportInterface $report): string;
}
