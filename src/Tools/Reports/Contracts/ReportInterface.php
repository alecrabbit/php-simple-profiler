<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:26
 */

namespace AlecRabbit\Tools\Reports\Contracts;

interface ReportInterface
{
    public const REPORT_FORMAT = '"%s": %s';
    public const REPORT_DIV = ' => ';
    public const REPORT_EXTENDED_SUFFIX = '%s(%s) ';

    public function __toString();
}
