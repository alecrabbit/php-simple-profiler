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
        $this->assertStart($start);
        $this->assertStop($stop);
    }

    /**
     * @param $start
     */
    protected function assertStart($start): void
    {
        if (!\is_float($start)) {
            throw new \RuntimeException('Start value is NOT ok. [' . typeOf($start) . ']');
        }
    }

    /**
     * @param $stop
     */
    protected function assertStop($stop): void
    {
        if (!is_float($stop)) {
            throw new \RuntimeException('Stop value is NOT ok. [' . typeOf($stop) . ']');
        }
    }
}
