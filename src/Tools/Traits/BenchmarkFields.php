<?php
/**
 * User: alec
 * Date: 01.12.18
 * Time: 20:33
 */

namespace AlecRabbit\Tools\Traits;

use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Timer;

trait BenchmarkFields
{
    /** @var array */
    protected $functions = [];

    /** @var Profiler */
    protected $profiler;

    /** @var int */
    protected $doneIterations = 0;

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
}
