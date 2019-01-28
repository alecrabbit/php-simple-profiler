<?php

namespace AlecRabbit\Tools;

use AlecRabbit\Exception\InvalidStyleException;
use AlecRabbit\Rewindable;
use AlecRabbit\Tools\Contracts\BenchmarkInterface;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Factory;
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

    use BenchmarkFields, Reportable;

    /** @var int */
    private $namingIndex = 1;
    /** @var Rewindable */
    private $rewindable;
    /** @var int */
    private $iterations;
    /** @var null|string */
    private $comment;
    /** @var bool */
    private $verbose;
    /** @var int */
    private $dots;
    /** @var array */
    private $names;
    /** @var string|null */
    private $humanReadableName;
    /** @var int */
    private $iterationsToBench;

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
        $this->dots = 0;
        $this->verbose = false;
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
        $this->resetReportObject();
    }

    /**
     * Launch benchmarking
     * @param bool $printReport
     * @throws InvalidStyleException
     */
    public function run(bool $printReport = false): void
    {
        if ($this->verbose) {
            echo
            sprintf(
                'Running benchmarks(Functions: %s, Repeat: %s):',
                $this->profiler->counter(self::ADDED)->getValue(),
                $this->iterations
            );
            echo PHP_EOL;
        }
        $this->execute();

        if ($this->verbose) {
            $this->erase();
            echo ' 100%' . PHP_EOL;
            echo '  λ   Done!' . PHP_EOL;
        }

        if ($printReport) {
            echo PHP_EOL;
            echo (string)$this->getReport();
            echo PHP_EOL;
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
        if ($this->verbose && 1 === ++$this->totalIterations % 5000) {
            $this->erase();
            echo '.';
            $a =
                str_pad(
                    Helper::percent($this->totalIterations / $this->iterationsToBench),
                    6,
                    ' ',
                    STR_PAD_LEFT
                );
            echo $a;
            if (++$this->dots > static::PG_WIDTH) {
                echo PHP_EOL;
                $this->dots = 0;
            }
        }
    }

    private function erase(): void
    {
        echo "\e[6D";
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
     * @return Benchmark
     */
    public function color(): self
    {
        Factory::enableColour(true);
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
     * @throws \Throwable
     */
    public function elapsed(): string
    {
        $theme = Factory::getThemedObject();
        return
            sprintf(
                'Done in: %s',
                $theme->yellow($this->getProfiler()->timer()->elapsed())
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
