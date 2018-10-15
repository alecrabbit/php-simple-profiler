<?php
/**
 * User: alec
 * Date: 15.10.18
 * Time: 3:54
 */

namespace AlecRabbit\Profiler\Contracts;


interface Strings
{
    const _NAME = 'name';
    const _AVG = 'avg';
    const _MIN = 'min';
    const _MAX = 'max';
    const _COUNT = 'count';
    const _LAST = 'last';
    const _EXTENDED = 'extended';

    const _COUNTERS = 'counters';
    const _TIMERS = 'timers';
    const _DEFAULT = 'default';

    const _NAME_FORMAT = '%s [%s]';

}