<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Contracts\ProfilerInterface;
use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\ProfilerReport;
use AlecRabbit\Tools\Reports\Traits\HasReport;
use AlecRabbit\Traits\DefaultableName;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class Profiler implements ProfilerInterface, ReportableInterface, Strings
{
    use DefaultableName, HasReport;

    /** @var Timer[] */
    private $timers = [];

    /** @var AbstractCounter[] */
    private $counters = [];

    /**
     * Profiler constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->counter(); // Create default counter
        $this->timer(); // Create default timer
        $this->report = (new ProfilerReport())->buildOn($this);
//        dump($this->timers);
    }

    /**
     * @param null|string $name
     * @param string ...$suffixes
     * @return AbstractCounter
     * @throws \Exception
     */
    public function counter(?string $name = null, string ...$suffixes): AbstractCounter
    {
        $name = $this->prepareName($name, $suffixes);
        return
            $this->counters[$name] ?? $this->counters[$name] = $this->makeCounter($name);
    }

    /**
     * @param null|string $name
     * @param array $suffixes
     * @return string
     */
    protected function prepareName(?string $name, array $suffixes): string
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
     * @param string $name
     * @return AbstractCounter
     * @throws \Exception
     */
    protected function makeCounter(string $name): AbstractCounter
    {
        if (DEFAULT_NAME === $name) {
            return new SimpleCounter($name);
        }
        return new ExtendedCounter($name);
    }

    /**
     * @param null|string $name
     * @param string ...$suffixes
     * @return Timer
     * @throws \Exception
     */
    public function timer(?string $name = null, string ...$suffixes): Timer
    {
        $name = $this->prepareName($name, $suffixes);
        return
            $this->timers[$name] ?? $this->timers[$name] = new Timer($name);
    }

    /**
     * @return AbstractTimer[]
     */
    public function getTimers(): array
    {
        return $this->timers;
    }

    /**
     * @return AbstractCounter[]
     */
    public function getCounters(): array
    {
        return $this->counters;
    }
}
