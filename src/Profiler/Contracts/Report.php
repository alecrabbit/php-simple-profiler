<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:26
 */

namespace AlecRabbit\Profiler\Contracts;


interface Report
{
    const REPORT_FORMAT = "'%s': %s";
    const REPORT_DIV = " => ";
    const REPORT_EXTENDED_SUFFIX = "%s(%s) ";
}