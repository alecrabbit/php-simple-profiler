<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:13
 */

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Contracts\ProfilerInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Traits\Reportable;
use AlecRabbit\Traits\DefaultableName;

class Profiler implements ProfilerInterface, ReportableInterface
{
    use Reportable, DefaultableName;

    /** @var Timer[] */
    private $timers = [];

    /** @var Counter[] */
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
     * @return Counter
     */
    public function counter(?string $name = null, string ...$suffixes): Counter
    {
        $name = $this->prepName($name, $suffixes);
        return
            $this->counters[$name] ?? $this->counters[$name] = new Counter($name);
    }

    /**
     * @param null|string $name
     * @param array $suffixes
     * @return string
     */
    private function prepName(?string $name, array $suffixes): string
    {
        $name = $this->default($name);
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
            sprintf(static::_NAME_FORMAT, $name, implode(', ', $suffixes));
    }

    /**
     * @param null|string $name
     * @param string ...$suffixes
     * @return Timer
     */
    public function timer(?string $name = null, string ...$suffixes): Timer
    {
        $name = $this->prepName($name, $suffixes);
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
     * @return Counter[]
     */
    public function getCounters(): array
    {
        return $this->counters;
    }
}
