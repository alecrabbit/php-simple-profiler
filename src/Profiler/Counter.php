<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:18
 */

namespace AlecRabbit\Profiler;


class Counter implements Contracts\Counter
{
    /** @var string */
    private $name;

    /** @var int */
    private $value;

    /**
     * Counter constructor.
     * @param string $name
     * @param int $value
     */
    public function __construct(string $name, int $value = 0)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @param int $times
     * @return int
     */
    public function bump(int $times = 1): int
    {
        if ($times < 1)
            $times = 1;
        $this->value += $times;
        return
            $this->value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function report(bool $extended = false): string
    {
        return
            sprintf(
                '%s %s',
                $this->getName(),
                $this->value
            );

    }
}