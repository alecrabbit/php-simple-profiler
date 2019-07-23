<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

class BenchmarkResult
{
    /** @var float */
    protected $mean;
    /** @var float */
    protected $delta;
    /** @var int */
    protected $numberOfMeasurements;

    public function __construct(float $mean, float $delta, int $numberOfMeasurements)
    {
        $this->mean = $mean;
        $this->delta = $delta;
        $this->numberOfMeasurements = $numberOfMeasurements;
    }

    /**
     * @return float
     */
    public function getMean(): float
    {
        return $this->mean;
    }

    /**
     * @return float
     */
    public function getDelta(): float
    {
        return $this->delta;
    }

    /**
     * @return int
     */
    public function getNumberOfMeasurements(): int
    {
        return $this->numberOfMeasurements;
    }
}