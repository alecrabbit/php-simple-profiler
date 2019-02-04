<?php
/**
 * User: alec
 * Date: 15.10.18
 * Time: 3:54
 */

namespace AlecRabbit\Tools\Contracts;

interface StringsInterface
{
    public const _COUNTERS = 'counters';
    public const _TIMERS = 'timers';

    public const NAME_FORMAT = '%s [%s]';

    public const ELAPSED = 'Elapsed';
    public const COUNT = 'Count';
    public const MAX = 'Max';
    public const MIN = 'Min';
    public const LAST = 'Last';
    public const AVERAGE = 'Average';
    public const TIMER = 'Timer';
    public const COUNTER = 'Counter';
    public const VALUE = 'Value';
    public const STEP = 'Step';
    public const DIFF = 'Diff';
    public const PATH = 'Path';
    public const LENGTH = 'Length';
}
