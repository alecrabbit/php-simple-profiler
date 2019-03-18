<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Internal;

class BenchmarkRelative
{
    /** @var int */
    private $rank;

    /** @var float */
    private $relative;

    /** @var float */
    private $average;

    /**
     * BenchmarkRelative constructor.
     * @param int $rank
     * @param float $relative
     * @param float $average
     */
    public function __construct(int $rank, float $relative, float $average)
    {
        $this->rank = $rank;
        $this->relative = $relative;
        $this->average = $average;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
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
