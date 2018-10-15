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

    public function counter(string $name = 'default', ?string ...$suffixes): Counter
    {
        if (!empty($suffixes))
            $name = sprintf('%s [%s]', $name, implode(', ', $suffixes));
        return
            $this->counters[$name] ?? $this->counters[$name] = new Counter($name);
    }

    public function timer(string $name = 'default', ?string ...$suffixes): Timer
    {
        if (!empty($suffixes))
            $name = sprintf('%s [%s]', $name, implode(', ', $suffixes));
        return
            $this->timers[$name] ?? $this->timers[$name] = new Timer($name);
    }

    // TODO separate logic and view
    public function report(bool $extended = false): iterable
    {
        $result = [];
        foreach ($this->counters as $counter) {
            $result[] = $counter->report($extended);
        }
        foreach ($this->timers as $timer) {
            $result[] = $timer->report($extended);
        }
        return
            $result;
    }

    private function format(array $objects, string $header = '', bool $extended = false)
    {
        $result = '';
        if (!empty($objects)) {
            $result .= $header . ':' . PHP_EOL;
            $n = 1;
            foreach ($objects as $obj) {
                $r = $obj->report($extended);
                if (!empty($r)) {
                    $result .=
                        sprintf(
                            "%s. %s %s",
                            $n,
                            $r,
                            PHP_EOL
                        );
                    $n++;
                }
            }
            $result .= PHP_EOL;
        }
        return
            $result;

    }
}