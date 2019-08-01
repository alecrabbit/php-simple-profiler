<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Internal;

class BenchmarkRelative
{
    /** @var int */
    private $rank;

    /** @var float */
    private $relative;

    /** @var null|BenchmarkResult */
    private $benchmarkResult;

    /**
     * BenchmarkRelative constructor.
     * @param int $rank
     * @param float $relative
     * @param null|BenchmarkResult $result
     */
    public function __construct(int $rank, float $relative, ?BenchmarkResult $result)
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
     * @return null|BenchmarkResult
     */
    public function getBenchmarkResult(): ?BenchmarkResult
    {
        return $this->benchmarkResult;
    }
}
