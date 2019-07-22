<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

class BenchmarkResult
{
    /** @var float */
    protected $mean;
    /** @var float */
    protected $delta;

    public function __construct(float $mean, float $delta)
    {
        $this->mean = $mean;
        $this->delta = $delta;
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
}