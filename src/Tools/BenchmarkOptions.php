<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

class BenchmarkOptions
{
    /** @var bool */
    protected $cli = false;

    public function __construct()
    {
        if (PHP_SAPI === 'cli') {
            $this->cli = true;
        }
    }

    /**
     * @return bool
     */
    public function isCli(): bool
    {
        return $this->cli;
    }
}
