<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use function AlecRabbit\Helpers\bounds;

class BenchmarkOptions
{
//    public const DIRECT_MEASUREMENTS = 1000;
//    public const INDIRECT_MEASUREMENTS = 1010;
//    protected const MAX_ITERATIONS = [
//        self::INDIRECT_MEASUREMENTS => 5,
//        self::DIRECT_MEASUREMENTS => 7,
//    ];

    /** @var bool */
    protected $cli = false;

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
        $this->setMaxIterations(5);
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
     */
    public function setMaxIterations(int $maxIterations): void
    {
        $this->maxIterations = (int)bounds($maxIterations, 1, 10);
    }

    /**
     * @return bool
     */
    public function isCli(): bool
    {
        return $this->cli;
    }
}
