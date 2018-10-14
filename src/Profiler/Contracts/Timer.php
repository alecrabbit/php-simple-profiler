<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:18
 */

namespace AlecRabbit\Profiler\Contracts;


interface Timer
{
    const UNIT_MICROSECONDS = 0;
    const UNIT_MILLISECONDS = 1;
    const UNIT_SECONDS = 2;
    const UNIT_MINUTES = 3;
    const UNIT_HOURS = 4;

}