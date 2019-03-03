<?php
/**
 * User: alec
 * Date: 01.12.18
 * Time: 20:33
 */

namespace AlecRabbit\Tools\Traits;

use AlecRabbit\Accessories\MemoryUsage\MemoryUsageReport;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Timer;

trait BenchmarkFields
{
    /** @var array */
    protected $functions = [];

    /** @var Profiler */
    protected $profiler;

    /** @var MemoryUsageReport */
    protected $memoryUsageReport;

    /** @var int */
    protected $doneIterations = 0;

    /** @var int */
    protected $doneIterationsCombined = 0;

    /** @var Timer */
    private $timer;


    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }

    /**
     * @return Profiler
     */
    public function getProfiler(): Profiler
    {
        return $this->profiler;
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
}
