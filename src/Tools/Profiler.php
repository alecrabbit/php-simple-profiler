<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:13
 */

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Contracts\ProfilerInterface as ProfilerContract;

class Profiler implements ProfilerContract
{
    /** @var Timer[] */
    private $timers = [];

    /** @var Counter[] */
    private $counters = [];

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
        $name = $name ?? static::_DEFAULT;
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
     * @param bool|null $formatted
     * @param bool|null $extended
     * @param int|null $units
     * @param int|null $precision
     * @return iterable
     */
    public function report(
        ?bool $formatted = null,
        ?bool $extended = null,
        ?int $units = null,
        ?int $precision = null
    ): iterable {
        $report = [];
        foreach ($this->counters as $counter) {
            $report[static::_COUNTERS][$counter->getName()] = $counter->report($extended);
        }
        foreach ($this->timers as $timer) {
            $report[static::_TIMERS][$timer->getName()] = $timer->report($formatted, $extended, $units, $precision);
        }
        return
            $report;
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
