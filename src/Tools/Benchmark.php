<?php

namespace AlecRabbit\Tools;

use AlecRabbit\Accessories\Rewindable;
use AlecRabbit\Tools\Contracts\BenchmarkInterface;
use AlecRabbit\Tools\Contracts\StringsInterface;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Traits\Reportable;
use AlecRabbit\Tools\Traits\BenchmarkFields;
use function AlecRabbit\typeOf;

class Benchmark implements BenchmarkInterface, ReportableInterface, StringsInterface
{
    use BenchmarkFields, Reportable;

    public const MIN_ITERATIONS = 100;
    public const DEFAULT_STEPS = 100;

    /** @var int */
    protected $advanceSteps = self::DEFAULT_STEPS;
    /** @var int */
    private $functionIndex = 1;
    /** @var Rewindable */
    private $rewindable;
    /** @var int */
    private $iterations;
    /** @var null|string */
    private $comment;
    /** @var string|null */
    private $humanReadableName;
    /** @var int */
    private $totalIterations = 0;
    /** @var null|callable */
    private $onStart;
    /** @var null|callable */
    private $onAdvance;
    /** @var null|callable */
    private $onFinish;
    /** @var int */
    private $advanceStep = 0;
    /** @var \Closure */
    private $generatorFunction;

    /**
     * Benchmark constructor.
     * @param int $iterations
     */
    public function __construct(?int $iterations = null)
    {
        $this->iterations = $this->refineIterations($iterations);

        $this->generatorFunction = function (int $iterations, int $i = 1): \Generator {
            while ($i <= $iterations) {
                yield $i++;
            }
        };

        $this->timer = new Timer();
        $this->initialize();
    }

    private function refineIterations(?int $iterations): int
    {
        $iterations = $iterations ?? self::MIN_ITERATIONS;
        if ($iterations < self::MIN_ITERATIONS) {
            throw new \RuntimeException(__CLASS__ . ': Iterations should greater then ' . self::MIN_ITERATIONS);
        }
        return $iterations;
    }

    /**
     * Resets Benchmark object clear
     */
    private function initialize(): void
    {
        $this->humanReadableName = null;
        $this->rewindable =
            new Rewindable(
                $this->generatorFunction,
                $this->iterations
            );
        $this->functions = [];
        $this->profiler = new Profiler();
    }

    /**
     * Resets Benchmark object clear
     */
    public function reset(): void
    {
        $this->initialize();
    }

    /**
     * Launch benchmarking
     */
    public function run(): Benchmark
    {
        if ($this->onStart) {
            ($this->onStart)();
        }
        $this->execute();
        if ($this->onFinish) {
            ($this->onFinish)();
        }
        return $this;
    }

    /**
     * Benchmarking
     */
    private function execute(): void
    {
        /** @var  BenchmarkFunction $f */
        foreach ($this->functions as $f) {
            if (!$f->execute()) {
                $this->totalIterations -= $this->iterations;
                continue;
            }
            $this->advanceStep = (int)($this->totalIterations / $this->advanceSteps);
            $this->bench($f);
            $this->profiler->counter(self::BENCHMARKED)->bump();
        }
    }

    /**
     * @param BenchmarkFunction $f
     */
    private function bench(BenchmarkFunction $f): void
    {
        $timer = $f->getTimer();
        $function = $f->getCallable();
        $args = $f->getArgs();
        foreach ($this->rewindable as $iteration) {
            $start = microtime(true);
            /** @noinspection DisconnectedForeachInstructionInspection */
            $function(...$args);
            $stop = microtime(true);
            $timer->bounds($start, $stop, $iteration);
            /** @noinspection DisconnectedForeachInstructionInspection */
            $this->progress();
        }
    }

    private function progress(): void
    {
        if ($this->onAdvance && 0 === ++$this->doneIterations % $this->advanceStep) {
            ($this->onAdvance)();
        }
    }

    /**
     * @param callable|null $onStart
     * @param callable|null $onAdvance
     * @param callable|null $onFinish
     * @return Benchmark
     */
    public function progressBar(
        callable $onStart = null,
        callable $onAdvance = null,
        callable $onFinish = null
    ): Benchmark {
        $this->onStart = $onStart;
        $this->onAdvance = $onAdvance;
        $this->onFinish = $onFinish;
        return $this;
    }

    /**
     * @param mixed $func
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
        $function =
            new BenchmarkFunction(
                $func,
                $this->refineName($func, $name),
                $this->functionIndex++,
                $args,
                $this->comment,
                $this->humanReadableName
            );
        $this->functions[$function->enumeratedName()] = $function;
        $this->humanReadableName = null;
        $this->comment = null;
        $this->profiler->counter(self::ADDED)->bump();
        $this->totalIterations += $this->iterations;
    }

    /**
     * @param callable $func
     * @param string $name
     * @return string
     */
    private function refineName($func, $name): string
    {
        if ($func instanceof \Closure) {
            $name = 'λ';
        }
        return $name;
    }

    /**
     * @param string $comment
     * @return Benchmark
     */
    public function withComment(string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @param string $name
     * @return Benchmark
     */
    public function useName(string $name): self
    {
        $this->humanReadableName = $name;
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
                $this->getTimer()->elapsed()
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
