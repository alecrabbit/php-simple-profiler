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
    protected $totalIterations = 0;
    /** @var bool */
    protected $withResults = false;
    /** @var array */
    private $exceptionMessages = [];

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
    public function getTotalIterations(): int
    {
        return $this->totalIterations;
    }

    /**
     * @return bool
     */
    public function isWithResults(): bool
    {
        return $this->withResults;
    }

    /**
     * @return array
     */
    public function getExceptionMessages(): array
    {
        return $this->exceptionMessages;
    }
}
