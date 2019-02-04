<?php

namespace AlecRabbit\Tools;

use AlecRabbit\Rewindable;
use AlecRabbit\Tools\Contracts\BenchmarkInterface;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Formatters\Helper;
use AlecRabbit\Tools\Reports\Traits\Reportable;
use AlecRabbit\Tools\Traits\BenchmarkFields;
use function AlecRabbit\brackets;
use function AlecRabbit\typeOf;

class Benchmark implements BenchmarkInterface, ReportableInterface
{
    protected const PG_WIDTH = 60;
    protected const ADDED = 'added';
    protected const BENCHMARKED = 'benchmarked';
    protected const ADVANCE_STEP = 5000;

    use BenchmarkFields, Reportable;

    /** @var int */
    private $namingIndex = 1;
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
    private $iterationsToBench;
    /** @var null|callable */
    private $onStart;
    /** @var null|callable */
    private $onAdvance;
    /** @var null|callable */
    private $onFinish;
    /** @var int */
    private $advanceStep;

    /**
     * Benchmark constructor.
     * @param int $iterations
     */
    public function __construct(int $iterations = 1000)
    {
        $this->iterations = $iterations;
        $this->reset();
    }

    /**
     * Resets Benchmark object clear
     */
    public function reset(): void
    {
        $this->names = [];
        $this->humanReadableName = null;
        $this->rewindable =
            new Rewindable(
                function (int $iterations, int $i = 1): \Generator {
                    while ($i <= $iterations) {
                        yield $i++;
                    }
                },
                $this->iterations
            );
        $this->resetFields();
    }

    private function expectedTotalIterations(): int
    {
        return count($this->functions) * $this->iterations;
    }

    /**
     * Launch benchmarking
     * @param bool $printReport
     */
    public function run(bool $printReport = false): void
    {
        if ($this->onStart) {
            ($this->onStart)();
        }
        $this->advanceStep = (int)($this->expectedTotalIterations() / 100);
        $this->execute();
        if ($this->onFinish) {
            ($this->onFinish)();
        }
    }

    /**
     * Benchmarking
     */
    private function execute(): void
    {
        /** @var  BenchmarkFunction $f */
        foreach ($this->functions as $name => $f) {
            $function = $f->getFunction();
            $args = $f->getArgs();
            $this->prepareResult($f, $function, $args);
            $timer = $f->getTimer();
            if ($f->getException()) {
                $timer->check();
                $this->iterationsToBench -= $this->iterations;
                continue;
            }
            foreach ($this->rewindable as $iteration) {
                $this->bench($timer, $function, $args, $iteration);
            }
            $this->profiler->counter(self::BENCHMARKED)->bump();
        }
    }

    /**
     * @param BenchmarkFunction $f
     * @param callable $function
     * @param array $args
     */
    private function prepareResult(BenchmarkFunction $f, callable $function, array $args): void
    {
        try {
            $result = $function(...$args);
        } catch (\Throwable $e) {
            $this->exceptionMessages[$f->getIndexedName()] = $result = brackets(typeOf($e)) . ': ' . $e->getMessage();
            $this->exceptions[$f->getIndexedName()] = $e;
            $f->setException($e);
        }
        $f->setResult($result);
    }

    /**
     * @param Timer $timer
     * @param callable $function
     * @param array $args
     * @param int $iteration
     */
    private function bench(Timer $timer, callable $function, array $args, int $iteration): void
    {
        $timer->start();
        $function(...$args);
        $timer->check($iteration);
        $this->progress();
    }

    private function progress(): void
    {
        if ($this->onAdvance && 1 === ++$this->totalIterations % $this->advanceStep) {
            ($this->onAdvance)();
        }
    }

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
        $function =
            new BenchmarkFunction(
                $func,
                $this->refineName($func, $name),
                $this->namingIndex++,
                $args,
                $this->comment,
                $this->humanReadableName
            );
        $this->functions[$function->enumeratedName()] = $function;
        $this->humanReadableName = null;
        $this->comment = null;
        $this->profiler->counter(self::ADDED)->bump();
        $this->iterationsToBench += $this->iterations;
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
