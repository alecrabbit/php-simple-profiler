<?php
/**
 * User: alec
 * Date: 15.10.18
 * Time: 3:54
 */

namespace AlecRabbit\Profiler\Contracts;

interface Strings
{
    public const _NAME = 'name';
    public const _AVG = 'avg';
    public const _MIN = 'min';
    public const _MAX = 'max';
    public const _COUNT = 'count';
    public const _LAST = 'last';
    public const _EXTENDED = 'extended';

    public const _COUNTERS = 'counters';
    public const _TIMERS = 'timers';
    public const _DEFAULT = 'default';

    public const _NAME_FORMAT = '%s [%s]';
}
