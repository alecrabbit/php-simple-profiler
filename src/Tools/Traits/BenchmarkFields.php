<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Traits;

use AlecRabbit\Accessories\MemoryUsage\MemoryUsageReport;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\SimpleCounter;
use AlecRabbit\Tools\Timer;

trait BenchmarkFields
{
    /** @var BenchmarkFunction[] */
    protected $functions = [];

//    /** @var Profiler */
//    protected $profiler;
//
    /** @var MemoryUsageReport */
    protected $memoryUsageReport;

    /** @var int */
    protected $doneIterations = 0;

    /** @var int */
    protected $doneIterationsCombined = 0;

    /** @var Timer */
    protected $timer;

    /** @var SimpleCounter */
    protected $added;

    /** @var SimpleCounter */
    protected $benchmarked;


    /**
     * @return BenchmarkFunction[]
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }

    /**
     * @return int
     */
    public function getDoneIterations(): int
    {
        return $this->doneIterations;
    }

    /**
     * @return Timer
     */
    public function getTimer(): Timer
    {
        return $this->timer;
    }

    /**
     * @return int
     */
    public function getDoneIterationsCombined(): int
    {
        return $this->doneIterationsCombined;
    }

    /**
     * @return MemoryUsageReport
     */
    public function getMemoryUsageReport(): MemoryUsageReport
    {
        return $this->memoryUsageReport;
    }

    /**
     * @return SimpleCounter
     */
    public function getAdded(): SimpleCounter
    {
        return $this->added;
    }

    /**
     * @return SimpleCounter
     */
    public function getBenchmarked(): SimpleCounter
    {
        return $this->benchmarked;
    }
}
