<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

class BenchmarkReport
{
    /** @var bool */
    protected $showReturns = false;
    /** @var null|BenchmarkFunction[] */
    protected $functions;

    public function withReturns(): self
    {
        $this->showReturns = true;
        return $this;
    }

    public function __toString(): string
    {
        return 'Report ' . $this->showReturns;
    }

    public function setFunctions(array $functions): self
    {
        $this->functions = $functions;
        return $this;
    }
}