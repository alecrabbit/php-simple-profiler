<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 11:04
 */

namespace AlecRabbit\Tools;

use AlecRabbit\Rewindable;
use AlecRabbit\Tools\Contracts\BenchmarkInterface;
use AlecRabbit\Tools\Internal\BenchmarkedFunction;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Traits\Reportable;
use AlecRabbit\Tools\Traits\BenchmarkFields;

class Benchmark implements BenchmarkInterface, ReportableInterface
{
    use BenchmarkFields, Reportable;

    /** @var int */
    private $namingIndex = 0;
    /** @var Rewindable */
    private $iterations; // todo rename field
    /** @var null|string */
    private $comment;

    public function __construct(int $iterations = 1000)
    {
        $this->iterations =
            new Rewindable(
                function (int $iterations, int $i = 1): \Generator {
                    while ($i <= $iterations) {
                        yield $i++;
                    }
                },
                $iterations
            );
        $this->profiler = new Profiler();
    }

    /**
     * Launch benchmarking
     */
    public function compare(): void
    {
        /** @var  BenchmarkedFunction $f */
        foreach ($this->functions as $name => $f) {
            $this->profiler->timer($name)->start();
            $function = $f->getFunction();
            $args = $f->getArgs();
            foreach ($this->iterations as $iteration) {
                /** @noinspection VariableFunctionsUsageInspection */
                /** @noinspection DisconnectedForeachInstructionInspection */
                \call_user_func($function, ...$args);
                $this->profiler->timer($name)->check($iteration);
                ++$this->iteration;
            }
        }
    }

    /**
     * @param callable $func
     * @param mixed ...$args
     */
    public function addFunction($func, ...$args): void
    {
        if (!\is_callable($func, false, $name)) {
            throw new \InvalidArgumentException('Function must be callable.');
        }
        $function = new BenchmarkedFunction($func, $name, $this->namingIndex++, $args, $this->comment);
        $this->comment = null;

        $this->functions[$function->getEnumeratedName()] = $function;
    }

    /**
     * @param string $name
     * @return Benchmark
     */
    public function withComment(string $name): self
    {
        $this->comment = $name;
        return $this;
    }

    /**
     * @return Profiler
     */
    public function getProfiler(): Profiler
    {
        return $this->profiler;
    }

    protected function prepareForReport(): void
    {
        $this->getProfiler()->report();
    }
}
