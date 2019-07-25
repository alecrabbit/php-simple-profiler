<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Internal;

use AlecRabbit\Tools\BenchmarkResult;

class BenchmarkRelative
{
    /** @var int */
    private $rank;

    /** @var float */
    private $relative;

    /** @var BenchmarkResult */
    private $benchmarkResult;

    /**
     * BenchmarkRelative constructor.
     * @param int $rank
     * @param float $relative
     * @param BenchmarkResult $result
     */
    public function __construct(int $rank, float $relative, BenchmarkResult $result)
    {
        $this->rank = $rank;
        $this->relative = $relative;
        $this->benchmarkResult = $result;
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
     * @return BenchmarkResult
     */
    public function getBenchmarkResult(): BenchmarkResult
    {
        return $this->benchmarkResult;
    }
}
