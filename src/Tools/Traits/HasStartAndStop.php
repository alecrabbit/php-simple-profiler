<?php declare(strict_types=1);


namespace AlecRabbit\Tools\Traits;

trait HasStartAndStop
{
    /** @var bool */
    protected $stopped = false;

    /** @var bool */
    protected $started = false;

    /**
     * @return bool
     */
    public function isStopped(): bool
    {
        return $this->stopped;
    }

    /**
     * @return bool
     */
    public function isNotStopped(): bool
    {
        return !$this->stopped;
    }

    /**
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->started;
    }

    /**
     * @return bool
     */
    public function isNotStarted(): bool
    {
        return !$this->started;
    }

    public function start(): void
    {
        $this->started = true;
    }

    public function stop(): void
    {
        $this->stopped = true;
    }
}
