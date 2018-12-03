<?php
/**
 * User: alec
 * Date: 01.12.18
 * Time: 20:33
 */

namespace AlecRabbit\Tools\Traits;

use AlecRabbit\Tools\Profiler;

trait BenchmarkFields
{
    /** @var array */
    protected $functions = [];
    /** @var Profiler */
    protected $profiler;
    /** @var int */
    protected $iteration = 0;

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
    public function getIteration(): int
    {
        return $this->iteration;
    }
}
