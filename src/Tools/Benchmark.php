<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 11:04
 */

namespace AlecRabbit\Tools;

use AlecRabbit\Profiler\Profiler;
use AlecRabbit\Profiler\Timer;
use AlecRabbit\Rewindable;

class Benchmark
{
    /** @var array */
    private $functions = [];
    /** @var Rewindable */
    private $iterations;
    /** @var Profiler */
    private $profiler;
    /** @var int */
    private $namingIndex;
    /** @var null|string */
    private $tmpName;

    public function __construct(int $iterations = 1000)
    {
        $this->iterations =
            new Rewindable(
                function (int $iterations): \Generator {
                    $i = 1;
                    while ($i <= $iterations) {
                        yield $i++;
                    }
                },
                $iterations
            );
        $this->profiler = new Profiler();
        $this->namingIndex = 0;
    }

    public function compare(): void
    {
        foreach ($this->functions as $name => $f) {
            $this->profiler->timer($name)->start();
            foreach ($this->iterations as $iteration) {
                [$function, $args] = $f;
                /** @noinspection VariableFunctionsUsageInspection */
                \call_user_func($function, ...$args);
                /** @noinspection DisconnectedForeachInstructionInspection */
                $this->profiler->timer($name)->check();
            }
        }
    }

    /**
     * @param callable $func
     * @param mixed ...$args
     */
    public function addFunction($func, ...$args): void
    {

        if (!\is_callable($func, false, $callable_name)) {
            throw new \InvalidArgumentException('Function must be callable');
        }
        if (null !== $this->tmpName) {
            $callable_name = $this->tmpName;
            $this->tmpName = null;
        }
        if (array_key_exists($callable_name, $this->functions)) {
            $callable_name .= '_' . ++$this->namingIndex;
        }
        $this->functions[$callable_name] = [$func, $args];
    }

    public function report(): array
    {
        $timers = $this->profiler->getTimers();
        $averages = $this->computeAverages($timers);

        $min = min($averages);
        return
            $this->computeRelatives($averages, $min);
    }

    /**
     * @param array $timers
     * @return array
     */
    private function computeAverages(array $timers): array
    {
        $averages = [];
        /** @var Timer $timer */
        foreach ($timers as $timer) {
            $averages[$timer->getName()] = $timer->getAvgValue();
        }
        return $averages;
    }

    /**
     * @param array $averages
     * @param float $min
     * @return array
     */
    private function computeRelatives(array $averages, float $min): array
    {
        $relatives = [];
        foreach ($averages as $name => $average) {
            $relatives[$name] = $average / $min;
        }
        asort($relatives);

        foreach ($relatives as $name => $relative) {
            $relatives[$name] =
                $this->toPercentage($relative) . ' ' .
                brackets(format_time($averages[$name]), BRACKETS_PARENTHESES);
        }
        return $relatives;
    }

    private function toPercentage(float $relative): string
    {
        return
            number_format($relative * 100, 1) . '%';
    }

    public function profilerReport(
        bool $formatted = true,
        bool $extended = true,
        ?int $units = null,
        ?int $precision = null
    ): iterable {
        return
            $this->profiler->report($formatted, $extended, $units, $precision);
    }

    public function withName(string $name): self
    {
        $this->tmpName = $name;
        return $this;
    }
}
