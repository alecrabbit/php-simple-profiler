<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 11:04
 */

namespace AlecRabbit\Tools;

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

    /**
     * Launch benchmarking
     */
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
        if (!\is_callable($func, false, $callableName)) {
            throw new \InvalidArgumentException('Function must be callable.');
        }
        if (null !== $this->tmpName) {
            $callableName = $this->tmpName;
            $this->tmpName = null;
        }
        if (array_key_exists($callableName, $this->functions)) {
            $callableName .= '_' . ++$this->namingIndex;
        }
        $this->functions[$callableName] = [$func, $args];
    }

    /**
     * @return array
     */
    public function report(): array
    {
        return
            $this->computeRelatives();
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
     * @return array
     */
    private function computeRelatives(): array
    {
        $averages = $this->computeAverages(
            $this->profiler->getTimers()
        );

        $min = min($averages);

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

    /**
     * @param float $relative
     * @return string
     */
    private function toPercentage(float $relative): string
    {
        return
            number_format($relative * 100, 1) . '%';
    }

    /**
     * @param bool $formatted
     * @param bool $extended
     * @param int|null $units
     * @param int|null $precision
     * @return iterable
     */
    public function profilerReport(
        bool $formatted = true,
        bool $extended = true,
        ?int $units = null,
        ?int $precision = null
    ): iterable {
        return
            $this->profiler->report($formatted, $extended, $units, $precision);
    }

    /**
     * @param string $name
     * @return Benchmark
     */
    public function withName(string $name): self
    {
        $this->tmpName = $name;
        return $this;
    }
}
