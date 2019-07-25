<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Accessories\Pretty;
use MathPHP\Exception\BadDataException;
use MathPHP\Exception\OutOfBoundsException;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

class Benchmark
{
    /** @var null|string */
    protected $comment;

    /** @var null|string */
    protected $name;

    /** @var BenchmarkOptions */
    protected $options;

    /** @var BenchmarkFunction[] */
    protected $functions = [];

    /** @var int */
    protected $index = 0;

    /** @var OutputInterface */
    protected $output;

    /** @var int */
    protected $maxIterations;

    /** @var BenchmarkResult[] */
    protected $results;

    public function __construct(BenchmarkOptions $options = null, ?OutputInterface $output = null)
    {
        $this->options = $options ?? new BenchmarkOptions();
        $this->output = $output ?? new ConsoleOutput();
        $this->maxIterations = $this->options->getMaxIterations();
    }

    /**
     * @param string $comment
     * @return Benchmark
     */
    public function withComment(string $comment): self
    {
        Assert::notWhitespaceOnly(
            $comment,
            'Expected a non-whitespace comment string. Got: "' . $comment . '"'
        );
        $this->comment = $comment;
        return $this;
    }

    /**
     * @param string $name
     * @return Benchmark
     */
    public function withName(string $name): self
    {
        Assert::notWhitespaceOnly(
            $name,
            'Expected a non-whitespace function name string. Got: "' . $name . '"'
        );
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $func
     * @param mixed ...$args
     */
    public function add($func, ...$args): void
    {
        $this->functions[] =
            new BenchmarkFunction(
                $func,
                $args,
                ++$this->index,
                $this->name,
                $this->comment
            );
        $this->name = null;
        $this->comment = null;
    }

    public function run(): BenchmarkReport
    {
        foreach ($this->functions as $function) {
            $this->message(
                sprintf(
                    ' Benchmarking function: "%s" %s: ',
                    $function->getHumanReadableName(),
                    $function->getComment()
                ),
                false
            );
            if (!$function->execute()) {
                $exception = $function->getException();
                if ($exception instanceof \Throwable) {
                    if ($this->options->isCli()) {
                        echo
                            sprintf(
                                ' Exception encountered: %s',
                                $exception->getMessage()
                            ) . PHP_EOL;
                    }
                }
                continue;
            }
            $result = $this->benchNew($function);
//            $result = MeasurementsResults::createResult($function->getResults());
//            $this->addResult($result);
            $this->message(
                sprintf(
                    'Result %s±%s',
                    Pretty::nanoseconds($result->getMean()),
                    Pretty::percent($result->getDeltaPercent())
                )
            );
        }
        return (new BenchmarkReport())->setFunctions($this->functions);
    }

    /**
     * @param string $message
     * @param bool $newline
     */
    protected function message(string $message, $newline = true): void
    {
        if ($this->options->isCli()) {
            $this->output->write($message, $newline);
        }
    }

    /**
     * @param BenchmarkFunction $f
     * @return BenchmarkResult
     * @throws BadDataException
     * @throws OutOfBoundsException
     */
    protected function benchNew(BenchmarkFunction $f): BenchmarkResult
    {
        $r = [];
        $this->warmUp($f);
        $n = 0;
        while ($n++ <= 6) {
            $r[] = $this->indirectBenchmark(100, $f);
        }
        $result = MeasurementsResults::createResult($r);
//        dump((string)$result);
        $n = 0;
        while ($n++ <= 4) {
            try {
                $benchmarkResult = $this->directBenchmark($this->getRevs($n, 5), $f, $result);
                $r[] = $benchmarkResult;
//                dump($benchmarkResult);
            } catch (BadDataException $e) {
//                $this->message('Result rejected');
            }
        }
        $result = MeasurementsResults::createResult($r);
        $f->setResult($result);
        return $result;
//        dump((string)$result);
//        dump($result);
    }

    /**
     * @param BenchmarkFunction $f
     * @param int $max
     */
    protected function warmUp(BenchmarkFunction $f, int $max = 3): void
    {
        $n = 0;
        while ($n++ <= $max) {
            $this->indirectBenchmark(100, $f);
        }
    }

    protected function indirectBenchmark(int $i, BenchmarkFunction $f): BenchmarkResult
    {
        $function = $f->getCallable();
        $args = $f->getArgs();

        $start = hrtime(true);
        $revs = $i;
        while ($i-- > 0) {
            $function(...$args);
        }
        return
            new BenchmarkResult((hrtime(true) - $start) / $revs, 0, $revs);
    }

//    protected function bench2(BenchmarkFunction $f): void
//    {
//        $function = $f->getCallable();
//        $args = $f->getArgs();
//        $n = 0;
//        while ($n <= $this->maxIterations) {
//            $revs = $this->getRevs($n);
//            $i = $revs;
//            $start = hrtime(true);
//            $r = null;
//            while ($i-- > 0) {
//                $r = $function(...$args);
//            }
//            $unequal = false;
//            if ($f->getReturn() !== $r) {
//                $unequal = true;
//            }
//            $measurement = hrtime(true) - $start;
//            $result = new BenchmarkResult($measurement / $revs, 0, $revs);
//            if ($revs > 500) {
//                $f->addResult($result);
//            }
//            $this->message(
//                sprintf(
//                    '   Iteration #%s %s±%s [%s] %s',
//                    $n,
//                    Pretty::nanoseconds($result->getMean()),
//                    Pretty::percent($result->getDeltaPercent()),
//                    $result->getNumberOfMeasurements(),
//                    $unequal ? 'unequal returns' : ''
//                )
//            );
//            $n++;
//        }
//    }

    /**
     * @param int $i
     * @param BenchmarkFunction $f
     * @param BenchmarkResult|null $previous
     * @return BenchmarkResult
     * @throws BadDataException
     * @throws OutOfBoundsException
     */
    protected function directBenchmark(int $i, BenchmarkFunction $f, ?BenchmarkResult $previous = null): BenchmarkResult
    {
        $function = $f->getCallable();
        $args = $f->getArgs();
        $measurements = [];
        while ($i > 0) {
            $start = hrtime(true);
            $function(...$args);
            $measurements[] = hrtime(true) - $start;
            $i--;
        }
        return MeasurementsResults::createResult($measurements, $previous);
    }

    /**
     * @param int $n
     * @param int|null $shift
     * @return int
     */
    protected function getRevs(int $n, ?int $shift = null): int
    {
        $shift = $shift ?? 0;
        if ($n <= 0) {
            return 1 + $shift;
        }
        if ($n > $this->maxIterations) {
            $n = $this->maxIterations;
        }
        if ($this->options->methodIsDirect()) {
            return 1 + $n ** ($n - 1) + $shift;
        }
        return 10 ** $n + $shift;
    }

    protected function addResult(BenchmarkResult $result): void
    {
        $this->results[] = $result;
    }

//    protected function bench(BenchmarkFunction $f): void
//    {
//        $function = $f->getCallable();
//        $args = $f->getArgs();
//        $n = 1;
//        while ($n <= $this->maxIterations) {
//            $revs = $this->getRevs($n);
//            $n++;
//
//            $i = $revs;
//            $measurements = [];
//            while ($i > 0) {
//                $start = hrtime(true);
//                $r = $function(...$args);
//                $measurements[] = hrtime(true) - $start;
//                $i--;
//            }
//            $result = MeasurementsResults::createResult($measurements);
//            if ($result->getDeltaPercent() < 0.02) {
//                $f->addResult($result);
//                $this->message(
//                    sprintf(
//                        '   Iteration #%s %s±%s %s[%s]',
//                        $n,
//                        Pretty::nanoseconds($result->getMean()),
//                        Pretty::percent($result->getDeltaPercent()),
//                        $result->getNumberOfMeasurements(),
//                        Pretty::percent(1 - $result->getRejectionsPercent())
//                    )
//                );
//            }
//        }
//    }
}
