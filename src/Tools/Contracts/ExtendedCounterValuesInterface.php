<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Contracts;

interface ExtendedCounterValuesInterface
{
    /**
     * @return int
     */
    public function getMax(): int;

    /**
     * @return int
     */
    public function getMin(): int;

    /**
     * @return int
     */
    public function getPath(): int;

    /**
     * @return int
     */
    public function getLength(): int;

    /**
     * @return int
     */
    public function getDiff(): int;

    /**
     * @return int
     */
    public function getBumpedBack(): int;
}
