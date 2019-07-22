<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

class BenchmarkReport
{
    /** @var bool */
    protected $showReturns = false;

    public function showReturns(): self
    {
        $this->showReturns = true;
        return $this;
    }

    public function __toString(): string
    {
        return 'Report ' . $this->showReturns;
    }
}