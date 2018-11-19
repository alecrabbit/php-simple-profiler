<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:18
 */

namespace AlecRabbit\Profiler\Contracts;

interface Timer extends Strings
{
    public const DEFAULT_PRECISION = 3;

    public const UNIT_MICROSECONDS = 10;
    public const UNIT_MILLISECONDS = 11;
    public const UNIT_SECONDS = 12;
    public const UNIT_MINUTES = 13;
    public const UNIT_HOURS = 14;
}
