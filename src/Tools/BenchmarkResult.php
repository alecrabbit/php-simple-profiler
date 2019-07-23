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
    /** @var int */
    protected $numberOfRejections;

    public function __construct(float $mean, float $delta, int $numberOfMeasurements, int $numberOfRejections = 0)
    {
        $this->mean = $mean;
        $this->delta = $delta;
        $this->numberOfMeasurements = $numberOfMeasurements;
        $this->numberOfRejections = $numberOfRejections;
    }

    public function getDeltaPercent(): float
    {
        return $this->getDelta() / $this->getMean();
    }

    /**
     * @return float
     */
    public function getDelta(): float
    {
        return $this->delta;
    }

    /**
     * @return float
     */
    public function getMean(): float
    {
        return $this->mean;
    }

    public function getRejectionsPercent(): float
    {
        return $this->getNumberOfRejections() / $this->getNumberOfMeasurements();
    }

    /**
     * @return int
     */
    public function getNumberOfRejections(): int
    {
        return $this->numberOfRejections;
    }

    /**
     * @return int
     */
    public function getNumberOfMeasurements(): int
    {
        return $this->numberOfMeasurements;
    }
}
