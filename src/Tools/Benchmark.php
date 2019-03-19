<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Accessories\MemoryUsage;
use AlecRabbit\Accessories\Rewindable;
use AlecRabbit\Tools\Contracts\BenchmarkInterface;
use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use AlecRabbit\Tools\Traits\BenchmarkFields;
use function AlecRabbit\typeOf;

class Benchmark extends Reportable implements BenchmarkInterface, Strings
{
    use BenchmarkFields;

    public const MIN_ITERATIONS = 100;
    public const DEFAULT_STEPS = 100;
    public const CLOSURE_NAME = 'λ';

    /** @var int */
    protected $advanceSteps = self::DEFAULT_STEPS;
    /** @var Rewindable */
    protected $rewindable;
    /** @var int */
    protected $iterations;
    /** @var null|string */
    protected $comment;
    /** @var string|null */
    protected $humanReadableName;
    /** @var int */
    protected $totalIterations = 0;
    /** @var null|callable */
    protected $onStart;
    /** @var null|callable */
    protected $onAdvance;
    /** @var null|callable */
    protected $onFinish;
    /** @var int */
    protected $advanceStep = 0;
    /** @var \Closure */
    protected $iterationNumberGenerator;
    /** @var bool */
    protected $launched = false;
    /** @var int */
    protected $functionIndex = 1;
    /** @var bool */
    protected $silent = false;

    /**
     * Benchmark constructor.
     * @param null|int $iterations
     * @param null|bool $silent
     * @throws \Exception
     */
    public function __construct(?int $iterations = null, ?bool $silent = null)
    {
        $this->iterations = $this->refineIterations($iterations);
        $this->silent = $silent ?? $this->silent;

        $this->iterationNumberGenerator =
            function (int $iterations, int $i = 1): \Generator {
                while ($i <= $iterations) {
                    yield $i++;
                }
            };

        $this->timer = new Timer(); // Timer to count benchmark process total time
        $this->initialize();
    }

    protected function refineIterations(?int $iterations): int
    {
        $iterations = $iterations ?? self::MIN_ITERATIONS;
        $this->assertIterations($iterations);
        return $iterations;
    }

    /**
     * @param int $iterations
     */
    protected function assertIterations(int $iterations): void
    {
        if ($iterations < self::MIN_ITERATIONS) {
            throw new \RuntimeException(
                __CLASS__ .
                ': Number of Iterations should be greater then ' .
                self::MIN_ITERATIONS
            );
        }
    }

    /**
     * Resets Benchmark object clear
     * @throws \Exception
     */
    protected function initialize(): void
    {
        unset($this->functions, $this->humanReadableName, $this->rewindable, $this->memoryUsageReport);

        $this->humanReadableName = null;
        $this->rewindable =
            new Rewindable(
                $this->iterationNumberGenerator,
                $this->iterations
            );
        $this->functions = [];
        $this->added = new SimpleCounter('added');
        $this->benchmarked = new SimpleCounter('benchmarked');
        $this->memoryUsageReport = MemoryUsage::report();
        $this->doneIterations = 0;
        $this->totalIterations = 0;
        $this->report = (new BenchmarkReport())->buildOn($this);
    }

    /**
     * Resets Benchmark object clear
     * @throws \Exception
     */
    public function reset(): void
    {
        $this->initialize();
    }

    /**
     * @param callable|null $onStart
     * @param callable|null $onAdvance
     * @param callable|null $onFinish
     * @return Benchmark
     */
    public function showProgressBy(
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
     * @throws \Exception
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
        $function->setShowReturns($this->isShowReturns());
        $this->functions[$function->enumeratedName()] = $function;
        $this->humanReadableName = null;
        $this->comment = null;
        $this->added->bump();
        $this->totalIterations += $this->iterations;
    }

    /**
     * @param callable $func
     * @param string $name
     * @return string
     */
    protected function refineName($func, $name): string
    {
        if ($func instanceof \Closure) {
            $name = self::CLOSURE_NAME;
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
     * @throws \Exception
     */
    public function stat(): string
    {
        return
            sprintf(
                'Done in: %s%s%s',
                $this->getTimer()->elapsed(),
                PHP_EOL,
                (string)$this->memoryUsageReport
            );
    }

    /**
     * @return Benchmark
     */
    public function showReturns(): Benchmark
    {
        $this->setShowReturns(true);
        foreach ($this->functions as $function) {
            $function->setShowReturns($this->isShowReturns());
        }
        return $this;
    }

    /**
     * @param bool $showReturns
     */
    public function setShowReturns(bool $showReturns): void
    {
        $this->showReturns = $showReturns;
    }

    /** {@inheritdoc} */
    protected function meetConditions(): void
    {
        if ($this->isNotLaunched()) {
            $this->run();
        }
    }

    /**
     * @return bool
     */
    public function isNotLaunched(): bool
    {
        return !$this->isLaunched();
    }

    /**
     * @return bool
     */
    public function isLaunched(): bool
    {
        return $this->launched;
    }

    /**
     * Launch benchmarking
     */
    public function run(): self
    {
        $this->displayComment();
        $this->launched = true;
        if ($this->onStart) {
            ($this->onStart)();
        }
        $this->execute();
        if ($this->onFinish) {
            ($this->onFinish)();
        }
        $this->doneIterationsCombined += $this->doneIterations;
        return $this;
    }

    protected function displayComment(): void
    {
        if (!$this->silent && null !== $this->comment) {
            $this->showComment($this->comment);
        }
    }

    protected function showComment(string $comment = ''): void
    {
        echo $comment . PHP_EOL;
    }

    /**
     * Benchmarking
     */
    protected function execute(): void
    {
        /** @var  BenchmarkFunction $f */
        foreach ($this->functions as $f) {
            if (!$f->execute()) {
                $this->totalIterations -= $this->iterations;
                continue;
            }
            $this->advanceStep = (int)($this->totalIterations / $this->advanceSteps);
            $this->bench($f);
            $this->benchmarked->bump();
        }
    }

    /**
     * @param BenchmarkFunction $f
     */
    protected function bench(BenchmarkFunction $f): void
    {
        $timer = $f->getTimer();
        $function = $f->getCallable();
        $args = $f->getArgs();
        foreach ($this->rewindable as $iteration) {
            $start = $timer->current();
            /** @noinspection DisconnectedForeachInstructionInspection */
            $function(...$args);
            $stop = $timer->current();
            $timer->bounds($start, $stop, $iteration);
            /** @noinspection DisconnectedForeachInstructionInspection */
            $this->progress();
        }
    }

    protected function progress(): void
    {
        $this->doneIterations++;
        if ($this->onAdvance && 0 === $this->doneIterations % $this->advanceStep) {
            ($this->onAdvance)();
        }
    }
}
