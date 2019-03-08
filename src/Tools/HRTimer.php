<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use const AlecRabbit\Helpers\Constants\INT_SIZE_64BIT;

class HRTimer extends AbstractTimer
{
    public const VALUE_COEFFICIENT = HRTIMER_VALUE_COEFFICIENT;

    public static $forceInstance = false;

    /**
     * @return int
     */
    public function current(): int
    {
        return
            (int)hrtime(true);
    }

    protected function checkConditions(): void
    {
        if (PHP_VERSION_ID < 70300 && false === static::$forceInstance) {
            throw new \RuntimeException(
                'Your php version is below 7.3.' .
                ' ' . static::class . ' uses hrtime() function of PHP ^7.3.' .
                ' There no sense in using polyfill function.' .
                ' Use Timer::class instance instead.'
            );
        }
        // @codeCoverageIgnoreStart
        if (PHP_INT_SIZE < INT_SIZE_64BIT) {
            throw new \RuntimeException(
                ' ' . static::class . ' can be used in 64bit environments only.' .
                ' You\'re using 32bit php installation'
            );
        }
        // @codeCoverageIgnoreEnd
    }
}
