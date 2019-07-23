<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Accessories\Pretty;
use const AlecRabbit\Helpers\Constants\UNIT_MICROSECONDS;
use MathPHP\Statistics\Average;
use MathPHP\Statistics\RandomVariable;
use Webmozart\Assert\Assert;

class Benchmark
{
    protected const REJECT_COEFFICIENT = 1.1;

    /** @var null|string */
    protected $comment;

    /** @var null|string */
    protected $name;

    /** @var BenchmarkOptions */
    protected $options;

    /** @var BenchmarkFunction[] */
    protected $functions = [];

    /** @var int */
    private $index = 0;

    public function __construct(BenchmarkOptions $options = null)
    {
        $this->options = $options ?? new BenchmarkOptions();
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
            if ($this->options->isCli()) {
                echo
                    sprintf(
                        ' Benchmarking function: "%s" %s',
                        $function->getHumanReadableName(),
                        $function->getComment()
                    ) . PHP_EOL;
            }
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
            $this->bench($function);
            if ($this->options->isCli()) {
                $result = MeasurementsResults::createResult($function->getResults());
                echo
                    sprintf(
                        'Result %s±%s',
                        Pretty::nanoseconds($result->getMean()),
                        Pretty::percent($result->getDeltaPercent()),
                    ) . PHP_EOL;
            }
        }
        return (new BenchmarkReport())->setFunctions($this->functions);
    }

    protected function bench(BenchmarkFunction $f): void
    {
        $function = $f->getCallable();
        $args = $f->getArgs();
        $n = 1;
        while ($n <= 7) {
            $revs = 1 + $n ** ($n - 1);
            $i = $revs;
            $measurements = [];
            while ($i > 0) {
                $start = hrtime(true);
                $r = $function(...$args);
                $measurements[] = hrtime(true) - $start;
                $i--;
            }
            $result = MeasurementsResults::createResult($measurements);
            $f->addResult($result);
            if ($this->options->isCli()) {
                echo
                    sprintf(
                        '   Iteration #%s %s±%s %s[%s]',
                        $n,
                        Pretty::nanoseconds($result->getMean()),
                        Pretty::percent($result->getDeltaPercent()),
                        $result->getNumberOfMeasurements(),
                        Pretty::percent(1 - $result->getRejectionsPercent())
                    ) . PHP_EOL;
            }

            $n++;
        }
    }
    protected function bench2(BenchmarkFunction $f): void
    {
        $function = $f->getCallable();
        $args = $f->getArgs();
        $n = 1;
        while ($n <= 7) {
            $revs = 1 + $n ** ($n - 1);
            $i = $revs;
            $start = hrtime(true);
            while ($i > 0) {
                $r = $function(...$args);
                $i--;
            }
            $measurement = hrtime(true) - $start;
            $result = new BenchmarkResult($measurement / $revs, 0, $revs);
            $f->addResult($result);
            if ($this->options->isCli()) {
                echo
                    sprintf(
                        '   Iteration #%s %s±%s %s[%s]',
                        $n,
                        Pretty::nanoseconds($result->getMean()),
                        Pretty::percent($result->getDeltaPercent()),
                        $result->getNumberOfMeasurements(),
                        Pretty::percent(1 - $result->getRejectionsPercent())
                    ) . PHP_EOL;
            }

            $n++;
        }
    }

    public function refine(array &$measurements, ?int &$rejections): void
    {
        $this->removeMax($measurements);
        $rejections = $rejections ?? 0;
        $meanCorr = Average::mean($measurements) * self::REJECT_COEFFICIENT;

        foreach ($measurements as $key => $value) {
            if ($value > $meanCorr) {
                unset($measurements[$key]);
                $rejections++;
            }
        }
    }

    protected function removeMax(array &$measurements): void
    {
        $max = max($measurements);
        unset($measurements[array_search($max, $measurements, true)]);
    }
}
