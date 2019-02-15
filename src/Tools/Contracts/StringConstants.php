<?php
/**
 * User: alec
 * Date: 15.10.18
 * Time: 3:54
 */

namespace AlecRabbit\Tools\Contracts;

interface StringConstants
{
    public const COUNTERS = 'counters';
    public const TIMERS = 'timers';

    public const ADDED = 'Added';
    public const BENCHMARKED = 'Benchmarked';
    public const BENCHMARK = 'Benchmark:';
    public const EXCEPTIONS = 'Exceptions:';

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
    public const BUMPED = 'Bumped';
    public const FORWARD = '+';
    public const BACKWARD = '-';

    public const RESULT = 'result';
    public const MEMORY = 'Memory';
    public const REAL = 'Real';
}
