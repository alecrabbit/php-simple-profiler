<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use const AlecRabbit\Helpers\Constants\INT_SIZE_64BIT;
use function AlecRabbit\typeOf;

class HRTimer extends AbstractTimer
{
    public const VALUE_COEFFICIENT = HRTIMER_VALUE_COEFFICIENT;

    /** @var bool */
    public static $ignoreVersionRestrictions = false;

    /**
     * @return int
     */
    public function current(): int
    {
        return
            (int)hrtime(true);
    }

    protected function checkEnvironment(): void
    {
        if (PHP_VERSION_ID < 70300 && false === static::$ignoreVersionRestrictions) {
            // `HRTimer::class` uses `hrtime()` function of PHP ^7.3.
            // There is almost no sense in using polyfill function.
            // If you're REALLY need to use HRTimer set `$ignoreVersionRestrictions` to true.
            // Otherwise use `Timer::class` instance instead.
            throw new \RuntimeException('[' . static::class . '] Your php version is below 7.3.0.');
        }
        // @codeCoverageIgnoreStart
        if (PHP_INT_SIZE < INT_SIZE_64BIT) {
            // `HRTimer::class` is designed and tested in 64bit environment
            // So it can be used in 64bit environments only
            // Maybe with some minor modification it can run on 32bit installations too
            throw new \RuntimeException(' You\'re using 32bit php installation.');
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param int $start
     * @param int $stop
     */
    protected function assertStartAndStop($start, $stop): void
    {
        $start_ok = false;
        $stop_ok = false;
        if (is_int($start)) {
            $start_ok = true;
        }
        if (is_int($stop)) {
            $stop_ok = true;
        }
        if (!$start_ok) {
            throw new \RuntimeException('Start value is NOT ok. [' . typeOf($start) . ']');
        }
        if (!$stop_ok) {
            throw new \RuntimeException('Stop value is NOT ok. [' . typeOf($stop) . ']');
        }
    }

}
