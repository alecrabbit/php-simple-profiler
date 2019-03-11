<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use function AlecRabbit\typeOf;

class Timer extends AbstractTimer
{
    /**
     * @return float
     */
    public function current(): float
    {
        return
            microtime(true);
    }

    /**
     * @param float $start
     * @param float $stop
     */
    protected function assertStartAndStop($start, $stop): void
    {
        $start_ok = false;
        $stop_ok = false;
        if (is_float($start)) {
            $start_ok = true;
        }
        if (is_float($stop)) {
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
