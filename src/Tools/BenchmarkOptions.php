<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use function AlecRabbit\Helpers\bounds;

class BenchmarkOptions
{
    /** @var bool */
    protected $cli = false;

    /** @var int */
    protected $maxIterations;
    /** @var int */
    protected $progressThreshold;

    /**
     * BenchmarkOptions constructor.
     */
    public function __construct()
    {
        if (PHP_SAPI === 'cli') {
            $this->cli = true;
        }
        $this->setMaxIterations(5);
    }

    /**
     * @return int
     */
    public function getProgressThreshold(): int
    {
        return $this->progressThreshold;
    }

    /**
     * @return int
     */
    public function getMaxIterations(): int
    {
        return $this->maxIterations;
    }

    /**
     * @param int $maxIterations
     * @return BenchmarkOptions
     */
    public function setMaxIterations(int $maxIterations): self
    {
        $this->maxIterations = (int)bounds($maxIterations, 1, 10);
        $this->progressThreshold = 10 + 1000 * $this->maxIterations;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCli(): bool
    {
        return $this->cli;
    }
}
