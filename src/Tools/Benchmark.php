<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use Webmozart\Assert\Assert;

class Benchmark
{
    /** @var null|string */
    protected $comment;

    /** @var null|string */
    protected $name;

    /** @var BenchmarkOptions */
    protected $options;

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
        Assert::notWhitespaceOnly($comment, 'Expected a non-whitespace comment string. Got: "' . $comment . '"');
        $this->comment = $comment;
        return $this;
    }

    /**
     * @param string $name
     * @return Benchmark
     */
    public function withName(string $name): self
    {
        Assert::notWhitespaceOnly($name, 'Expected a non-whitespace function name string. Got: "' . $name . '"');
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $func
     * @param mixed ...$args
     */
    public function add($func, ...$args): void
    {
        $this->comment = null;
        $this->name = null;
    }

    public function execute(): BenchmarkReport
    {
        return new BenchmarkReport();
    }
}