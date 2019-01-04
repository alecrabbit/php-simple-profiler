<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Internal;

class BenchmarkRelative
{
    /** @var float */
    private $relative;
    /** @var float */
    private $average;

    /**
     * BenchmarkRelative constructor.
     * @param float $relative
     * @param float $average
     */
    public function __construct(float $relative, float $average)
    {
        $this->relative = $relative;
        $this->average = $average;
    }

    /**
     * @return float
     */
    public function getRelative(): float
    {
        return $this->relative;
    }

    /**
     * @return float
     */
    public function getAverage(): float
    {
        return $this->average;
    }
}
