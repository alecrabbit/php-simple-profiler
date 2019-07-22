<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use MathPHP\Statistics\Average;
use MathPHP\Statistics\RandomVariable;
use Webmozart\Assert\Assert;

class Benchmark
{
    protected const NUMBER_OF_MEASUREMENTS = 5000;

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
            if (!$function->execute()) {
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
        while ($n++ <= 5) {
            $i = 2 ** ($n * 2) + 20;
            $measurements = [];
//            dump($n, $i, $measurements);
            while ($i > 0) {
                $start = hrtime(true);
                $function(...$args);
                $stop = hrtime(true);
                $measurements[] = $stop - $start;
                $i--;
            }
//            dump($measurements);
            $this->removeMaxAndMin($measurements);
            $mean = Average::mean($measurements);
            $standardErrorOfTheMean = RandomVariable::standardErrorOfTheMean($measurements);
            $tValue = TDistribution::tValue(count($measurements));

            $f->addResult(new BenchmarkResult($mean, $standardErrorOfTheMean * $tValue));
        }
    }

    protected function removeMaxAndMin(array &$measurements): void
    {

        sort($measurements);
        $measurements = array_slice($measurements, 5, -5);
    }

}