<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:13
 */

namespace AlecRabbit\Profiler;


use AlecRabbit\Profiler\Contracts\Profiler as ProfilerContract;

class Profiler implements ProfilerContract
{
    /** @var Timer[] */
    private $timers = [];

    /** @var Counter[] */
    private $counters = [];

    public function counter(?string $name = null, ?string ...$suffixes): Counter
    {
        $name = $this->prepName($name, $suffixes);
        return
            $this->counters[$name] ?? $this->counters[$name] = new Counter($name);
    }

    private function prepName($name, $suffixes): string
    {
        $name = $name ?? static::_DEFAULT;
        if (!empty($suffixes))
            return $this->formatName($name, $suffixes);
        return $name;
    }

    protected function formatName($name, $suffixes): string
    {
        return
            sprintf(static::_NAME_FORMAT, $name, implode(', ', $suffixes));
    }

    public function timer(?string $name = null, ?string ...$suffixes): Timer
    {
        $name = $this->prepName($name, $suffixes);
        return
            $this->timers[$name] ?? $this->timers[$name] = new Timer($name);
    }

    public function report(?bool $formatted = null, ?bool $extended = null, ?int $units = null, ?int $precision = null): iterable
    {
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
}