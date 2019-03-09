<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Contracts;

interface CounterValuesInterface
{
    /**
     * @return int
     */
    public function getValue(): int;

    /**
     * @return int
     */
    public function getInitialValue(): int;

    /**
     * @return int
     */
    public function getStep(): int;

    /**
     * @return int
     */
    public function getBumped(): int;
}
