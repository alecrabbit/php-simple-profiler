<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:18
 */

namespace AlecRabbit\Profiler\Contracts;


interface Timer extends Strings
{
    const DEFAULT_PRECISION = 3;

    const UNIT_MICROSECONDS = 10;
    const UNIT_MILLISECONDS = 11;
    const UNIT_SECONDS = 12;
    const UNIT_MINUTES = 13;
    const UNIT_HOURS = 14;

}