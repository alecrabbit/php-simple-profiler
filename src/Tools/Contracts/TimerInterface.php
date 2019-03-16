<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Contracts;

use AlecRabbit\Tools\AbstractTimer;

interface TimerInterface extends TimerValuesInterface
{
    /**
     * @return mixed
     */
    public function current();

    /**
     * @param int|float $start
     * @param int|float $stop
     * @param null|int $iterationNumber
     * @return AbstractTimer
     */
    public function bounds($start, $stop, ?int $iterationNumber = null): AbstractTimer;

    /**
     * @return callable
     */
    public function getTimeFunction(): callable;
}
