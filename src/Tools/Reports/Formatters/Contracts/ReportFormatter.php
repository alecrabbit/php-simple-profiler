<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:23
 */

namespace AlecRabbit\Tools\Reports\Formatters\Contracts;

interface ReportFormatter
{
    /**
     * @return string
     */
    public function getString(): string;
}
