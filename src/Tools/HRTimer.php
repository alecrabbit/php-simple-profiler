<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Reports\HRTimerReport;

class HRTimer extends AbstractTimer
{
    public const VALUE_COEFFICIENT = 1000000000;

    /**
     * @return int
     */
    public function current(): int
    {
        return
            (int)hrtime(true);
    }
}
