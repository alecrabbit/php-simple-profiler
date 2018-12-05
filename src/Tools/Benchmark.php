<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 11:04
 */

namespace AlecRabbit\Tools;

use AlecRabbit\Rewindable;
use AlecRabbit\Tools\Contracts\BenchmarkInterface;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Traits\Reportable;
use AlecRabbit\Tools\Traits\BenchmarkFields;
use function AlecRabbit\brackets;
use function AlecRabbit\typeOf;

class Benchmark implements BenchmarkInterface, ReportableInterface
{
    use BenchmarkFields, Reportable;

    /** @var int */
    private $namingIndex = 0;
    /** @var Rewindable */
    private $rewindable;
    /** @var int */
    private $iterations = 0;
    /** @var null|string */
    private $comment;
    /** @var bool */
    private $verbose = false;

    private $exceptionMessages = [];
    private $errorState = false;

    /**
     * Benchmark constructor.
     * @param int $iterations
     */
    public function __construct(int $iterations = 1000)
    {
        $this->iterations = $iterations;
        $this->rewindable =
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
     * @param bool $report
     */
    public function run(bool $report = false): void
    {
        if ($this->verbose) {
            $this->verboseRun();
        } else {
            $this->nonVerboseRun();
        }
        if ($report) {
            echo (string)$this->getReport();
            echo PHP_EOL;
        }
    }

    /**
     * Launch benchmarking in verbose mode
     */
    private function verboseRun(): void
    {
        echo
        sprintf(
            'Running benchmarks(%s):',
            $this->iterations
        );
        echo PHP_EOL;
        /** @var  BenchmarkFunction $f */
        foreach ($this->functions as $name => $f) {
            $function = $f->getFunction();
            $args = $f->getArgs();
            $this->prepareResult($f, $function, $args);
            $timer = $this->profiler->timer($name);
            $timer->start();
            if ($this->errorState) {
                $this->errorState = false;
                $timer->check();
                continue;
            }
            foreach ($this->rewindable as $iteration) {
                /** @noinspection VariableFunctionsUsageInspection */
                /** @noinspection DisconnectedForeachInstructionInspection */
                \call_user_func($function, ...$args);
                $timer->check($iteration);
                ++$this->totalIterations;
                if (1 === $this->totalIterations % 5000) {
                    echo '.';
                }
            }
            $this->profiler->counter()->bump();
        }
        echo PHP_EOL;
        echo PHP_EOL;
    }

    /**
     * @param BenchmarkFunction $f
     * @param callable $function
     * @param array $args
     */
    private function prepareResult(BenchmarkFunction $f, callable $function, array $args): void
    {
        if ($this->withResults) {
            try {
                /** @noinspection VariableFunctionsUsageInspection */
                $f->setResult(\call_user_func($function, ...$args));
            } catch (\Throwable $e) {
                $this->exceptionMessages[$f->getName()] = $message = $e->getMessage();
                $this->errorState = true;
                $f->setResult(brackets(typeOf($e)) . ': ' . $message);
            }
        }
    }

    /**
     * Launch benchmarking in verbose mode
     */
    private function nonVerboseRun(): void
    {
        /** @var  BenchmarkFunction $f */
        foreach ($this->functions as $name => $f) {
            $function = $f->getFunction();
            $args = $f->getArgs();
            $this->prepareResult($f, $function, $args);
            $timer = $this->profiler->timer($name);
            $timer->start();
            if ($this->errorState) {
                $this->errorState = false;
                $timer->check();
                continue;
            }
            foreach ($this->rewindable as $iteration) {
                /** @noinspection VariableFunctionsUsageInspection */
                /** @noinspection DisconnectedForeachInstructionInspection */
                \call_user_func($function, ...$args);
                $timer->check($iteration);
                ++$this->totalIterations;
            }
            $this->profiler->counter()->bump();
        }
    }

    /**
     * @return Benchmark
     */
    public function verbose(): self
    {
        $this->verbose = true;
        return $this;
    }

    /**
     * @param callable $func
     * @param mixed ...$args
     */
    public function addFunction($func, ...$args): void
    {
        if (!\is_callable($func, false, $name)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '\'%s\' is NOT callable. Function must be callable. Type of "%s" provided instead.',
                    $name,
                    typeOf($func)
                )
            );
        }
        $function = new BenchmarkFunction($func, $name, $this->namingIndex++, $args, $this->comment);
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
     * @return Benchmark
     */
    public function returnResults(): self
    {
        $this->withResults = true;
        return $this;
    }

    /**
     * @return string
     */
    public function elapsed(): string
    {
        return
            sprintf(
                'Done in: %s',
                $this->getProfiler()->timer()->elapsed()
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareForReport(): void
    {
        $this->getProfiler()->getReport();
    }
}
