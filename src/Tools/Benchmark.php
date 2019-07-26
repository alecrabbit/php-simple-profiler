<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use function AlecRabbit\Helpers\bounds;
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
        $this->message('Benchmarking: ');

        foreach ($this->functions as $function) {
            $this->message(
                sprintf(
                    'Function %s',
                    mb_str_pad('\'' . $function->getAssignedName() . '\'', 20)
                ),
                false
            );
            if (!$function->execute()) {
                $exception = $function->getException();
                if ($exception instanceof \Throwable) {
                    $this->message(
                        sprintf(
                            'Exception[%s]: %s ',
                            get_class($exception),
                            $exception->getMessage()
                        )
                    );
                }
                continue;
            }
            $result = $this->bench($function);
            $this->message(' ' . $result);
        }
        $this->message('');
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
    protected function bench(BenchmarkFunction $f): BenchmarkResult
    {
        $r = [];
        $this->warmUp($f);
        $n = 0;
        while ($n++ <= 6) {
            $r[] = $this->indirectBenchmark(1000, $f);
        }
        $result = MeasurementsResults::createResult($r);
        $n = 0;
        while ($n <= $this->maxIterations) {
            try {
                $benchmarkResult = $this->directBenchmark($this->getRevs($n, 5), $f, $result);
                $r[] = $benchmarkResult;
            } catch (BadDataException $e) {
                // Result rejected
//                $this->message('Result rejected');
            }
            $n++;
        }
        $result = MeasurementsResults::createResult($r);
        $f->setResult($result);
        return $result;
    }

    /**
     * @param BenchmarkFunction $f
     * @param int $max
     */
    protected function warmUp(BenchmarkFunction $f, int $max = 3): void
    {
        $n = 0;
        while ($n++ <= $max) {
            $this->indirectBenchmark(1000, $f);
        }
    }

    protected function indirectBenchmark(int $i, BenchmarkFunction $f): BenchmarkResult
    {
        dump($i);
        $function = $f->getCallable();
        $args = $f->getArgs();

        $revs = $i;
        $start = hrtime(true);
        while ($i-- > 0) {
            $function(...$args);
        }
        $stop = hrtime(true) - $start;
        $this->progress();
        return
            new BenchmarkResult($stop / $revs, 0, $revs);
    }

    protected function progress(): void
    {
        $this->message('.', false);
    }

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
        dump($i);

        $function = $f->getCallable();
        $args = $f->getArgs();
        $measurements = [];
        $done = 0;
        while ($i > 0) {
            $start = hrtime(true);
            $function(...$args);
            $measurements[] = hrtime(true) - $start;
            $done++;
            if (0 === $done % 2500) {
                $this->progress();
            }
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
        $n = (int)bounds($n, 1, 5);
        return 10 ** $n + $shift;
    }

    protected function addResult(BenchmarkResult $result): void
    {
        $this->results[] = $result;
    }

}
