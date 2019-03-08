<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

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
}
