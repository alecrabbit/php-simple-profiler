<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Contracts;

interface TimerInterface extends TimerValuesInterface
{
    /**
     * @return mixed
     */
    public function current();
}
