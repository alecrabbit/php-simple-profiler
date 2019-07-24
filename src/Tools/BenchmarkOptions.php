<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use function AlecRabbit\Helpers\bounds;

class BenchmarkOptions
{
    public const DIRECT_MEASUREMENTS = 1000;
    public const INDIRECT_MEASUREMENTS = 1010;
    protected const MAX_ITERATIONS = [
        self::INDIRECT_MEASUREMENTS => 5,
        self::DIRECT_MEASUREMENTS => 7,
    ];

    /** @var bool */
    protected $cli = false;

    /** @var int */
    protected $method;

    /** @var int */
    protected $maxIterations;

    /**
     * BenchmarkOptions constructor.
     */
    public function __construct()
    {
        if (PHP_SAPI === 'cli') {
            $this->cli = true;
        }
        $this->setMethod(self::INDIRECT_MEASUREMENTS);
    }

    /**
     * @return int
     */
    public function getMaxIterations(): int
    {
        return $this->maxIterations;
    }

//    /**
//     * @param int $maxPow
//     */
//    public function setMaxIterations(int $maxPow): void
//    {
//        $this->maxIterations = (int)bounds($maxPow, 1, 8);
//    }
//
    /**
     * @return bool
     */
    public function isCli(): bool
    {
        return $this->cli;
    }

    /**
     * @param int $method
     * @return BenchmarkOptions
     */
    public function setMethod(int $method): self
    {
        $this->method = $method;
        $this->maxIterations = self::MAX_ITERATIONS[$this->method];
        return $this;
    }

    /**
     * @return bool
     */
    public function methodIsDirect(): bool
    {
        return self::DIRECT_MEASUREMENTS === $this->method;
    }

    /**
     * @return bool
     */
    public function methodIsIndirect(): bool
    {
        return self::INDIRECT_MEASUREMENTS === $this->method;
    }
}
