<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Contracts\ProfilerInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Traits\HasReport;
use AlecRabbit\Traits\DefaultableName;

class Profiler implements ProfilerInterface, ReportableInterface
{
    use HasReport, DefaultableName;

    /** @var Timer[] */
    private $timers = [];

    /** @var ExtendedCounter[] */
    private $counters = [];

    public function __construct()
    {
        // Create "default" counter
        $this->counter();
        // Create "default" timer
        $this->timer();
    }

    /**
     * @param null|string $name
     * @param string ...$suffixes
     * @return ExtendedCounter
     */
    public function counter(?string $name = null, string ...$suffixes): ExtendedCounter
    {
        $name = $this->prepareName($name, $suffixes);
        return
            $this->counters[$name] ?? $this->counters[$name] = new ExtendedCounter($name);
    }

    /**
     * @param null|string $name
     * @param array $suffixes
     * @return string
     */
    private function prepareName(?string $name, array $suffixes): string
    {
        $name = $this->defaultName($name);
        if (!empty($suffixes)) {
            return $this->formatName($name, $suffixes);
        }
        return $name;
    }

    /**
     * @param string $name
     * @param array $suffixes
     * @return string
     */
    protected function formatName(string $name, array $suffixes): string
    {
        return
            sprintf(static::NAME_FORMAT, $name, implode(', ', $suffixes));
    }

    /**
     * @param null|string $name
     * @param string ...$suffixes
     * @return Timer
     */
    public function timer(?string $name = null, string ...$suffixes): Timer
    {
        $name = $this->prepareName($name, $suffixes);
        return
            $this->timers[$name] ?? $this->timers[$name] = new Timer($name);
    }

    /**
     * @return Timer[]
     */
    public function getTimers(): array
    {
        return $this->timers;
    }

    /**
     * @return ExtendedCounter[]
     */
    public function getCounters(): array
    {
        return $this->counters;
    }
}
