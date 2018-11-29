<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:18
 */

namespace AlecRabbit\Tools\Contracts;

interface TimerInterface extends StringsInterface
{
    public const DEFAULT_PRECISION = DEFAULT_PRECISION;

    public const UNIT_MICROSECONDS = UNIT_MICROSECONDS;
    public const UNIT_MILLISECONDS = UNIT_MILLISECONDS;
    public const UNIT_SECONDS = UNIT_SECONDS;
    public const UNIT_MINUTES = UNIT_MINUTES;
    public const UNIT_HOURS = UNIT_HOURS;
}
