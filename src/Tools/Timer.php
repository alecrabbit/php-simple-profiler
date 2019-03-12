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
     * @param float $start
     */
    protected function assertStart(/** @scrutinizer ignore-unused */ float $start): void
    {
        // Intentionally left blank
    }

    /**
     * @param float $stop
     */
    protected function assertStop(/** @scrutinizer ignore-unused */ float $stop): void
    {
        // Intentionally left blank
    }
}
