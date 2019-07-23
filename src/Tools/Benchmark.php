<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Accessories\Pretty;
use MathPHP\Statistics\Average;
use MathPHP\Statistics\RandomVariable;
use Webmozart\Assert\Assert;

class Benchmark
{
//    protected const NUMBER_OF_MEASUREMENTS = 5000;
    protected const REJECT_COEFFICIENT = 1.05;

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

    public function __construct(BenchmarkOptions $options)
    {
        $this->options = $options;
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
                        'Benchmarking function: "%s"',
                        $function->getHumanReadableName()
                    ) . PHP_EOL;
            }
            if (!$function->execute()) {
                $exception = $function->getException();
                if ($exception instanceof \Throwable) {
                    if ($this->options->isCli()) {
                        echo
                            sprintf(
                                'Exception encountered: %s',
                                $exception->getMessage()
                            ) . PHP_EOL;
                    }
                }
                continue;
            }
            $this->bench($function);
        }
        return (new BenchmarkReport())->setFunctions($this->functions);
    }

    protected function bench(BenchmarkFunction $f): void
    {
        $function = $f->getCallable();
        $args = $f->getArgs();
        $n = 1;
        while ($n <= 6) {

            $i = 2 ** ($n * 2) * 2;
            $measurements = [];
//            dump($n, $i, $measurements);
            while ($i > 0) {
                $start = hrtime(true);
                $function(...$args);
                $measurements[] = hrtime(true) - $start;
                $i--;
            }
//            dump($measurements);
            $this->refine($measurements);
            $mean = Average::mean($measurements);
            $standardErrorOfTheMean = RandomVariable::standardErrorOfTheMean($measurements);
            $numberOfMeasurements = count($measurements);
            $tValue = TDistribution::tValue($numberOfMeasurements);

            $result = new BenchmarkResult($mean, $standardErrorOfTheMean * $tValue, $numberOfMeasurements);
            $f->addResult($result);
            if ($this->options->isCli()) {
                echo
                    sprintf(
                        'Iteration #%s %s %s±%s',
                        $n,
                        $result->getNumberOfMeasurements(),
                        $result->getMean(),
                        Pretty::nanoseconds($result->getDelta()),
                        ) . PHP_EOL;
            }

            $n++;
        }
    }

    public function refine(array &$measurements): void
    {
        $this->removeMax($measurements);

        $meanCorr = Average::mean($measurements) * self::REJECT_COEFFICIENT;

        foreach ($measurements as $key => $value) {
            if ($value > $meanCorr) {
                unset($measurements[$key]);
            }
        }
    }

    protected function removeMax(array &$measurements): void
    {
        $max = max($measurements);
        unset($measurements[array_search($max, $measurements, true)]);
    }

}