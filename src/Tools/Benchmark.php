<?php

namespace AlecRabbit\Tools;

use AlecRabbit\Rewindable;
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

    /** @var int */
    private $functionIndex = 1;
    /** @var Rewindable */
    private $rewindable;
    /** @var int */
    private $iterations;
    /** @var null|string */
    private $comment;
    /** @var array */
    private $names;
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
    /** @var int */
    protected $advanceSteps = 100;
    /** @var \Closure */
    private $generatorFunction;

    /**
     * Benchmark constructor.
     * @param int $iterations
     */
    public function __construct(int $iterations = 1000)
    {
        $this->generatorFunction = function (int $iterations, int $i = 1): \Generator {
            while ($i <= $iterations) {
                yield $i++;
            }
        };

        $this->iterations = $iterations;
        $this->timer = new Timer();
        $this->initialize();
    }

    /**
     * Resets Benchmark object clear
     */
    public function reset(): void
    {
        $this->initialize();
    }

    /**
     * Resets Benchmark object clear
     */
    private function initialize(): void
    {
        $this->names = [];
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
        if (in_array($name, $this->names, true)) {
            throw new \InvalidArgumentException(sprintf('Name "%s" is not unique', $name));
        }
        $this->names[] = $name;
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
