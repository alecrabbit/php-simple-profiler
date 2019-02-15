<?php
/**
 * User: alec
 * Date: 03.12.18
 * Time: 22:24
 */

namespace AlecRabbit\Tools\Internal;

use AlecRabbit\Tools\Timer;
use AlecRabbit\Traits\GettableName;

/**
 * Class BenchmarkFunction
 * @package AlecRabbit
 * @internal
 */
class BenchmarkFunction
{
    use GettableName;

    /** @var callable */
    private $callable;

    /** @var int */
    private $index;

    /** @var array */
    private $args;

    /** @var mixed */
    private $result;

    /** @var null|string */
    private $comment;

    /** @var null|string */
    private $humanReadableName;

    /** @var \Throwable|null */
    private $exception;

    /** @var Timer */
    private $timer;

    /** @var null|BenchmarkRelative */
    private $benchmarkRelative;

    /**
     * BenchmarkFunction constructor.
     * @param callable $func
     * @param string $name
     * @param int $index
     * @param array $args
     * @param null|string $comment
     * @param null|string $humanReadableName
     */
    public function __construct(
        $func,
        string $name,
        int $index,
        array $args,
        ?string $comment = null,
        ?string $humanReadableName = null
    ) {
        $this->callable = $func;
        $this->name = $name;
        $this->index = $index;
        $this->args = $args;
        $this->comment = $comment;
        $this->humanReadableName = $humanReadableName;
        $this->timer = new Timer($this->getIndexedName());
    }

    /**
     * @return string
     */
    public function getIndexedName(): string
    {
        return "⟨{$this->getIndex()}⟩ {$this->getName()}";
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function comment(): string
    {
        return $this->comment ?? '';
//        return $this->comment ? str_decorate($this->comment, '"') : '';
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return callable
     */
    public function getCallable(): callable
    {
        return $this->callable;
    }

    public function enumeratedName(): string
    {
        return $this->getIndexedName();
//        return
//            brackets((string)$this->index, BRACKETS_ANGLE) . ' ' . $this->name;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }

    /**
     * @return Timer
     */
    public function getTimer(): Timer
    {
        return $this->timer;
    }

    /**
     * @return null|string
     */
    public function humanReadableName(): ?string
    {
        return $this->humanReadableName ?? $this->getIndexedName();
    }

    /**
     * @return null|\Throwable
     */
    public function getException(): ?\Throwable
    {
        return $this->exception;
    }

    /**
     * @param \Throwable $exception
     * @return BenchmarkFunction
     */
    public function setException(\Throwable $exception): BenchmarkFunction
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * @return null|BenchmarkRelative
     */
    public function getBenchmarkRelative(): ?BenchmarkRelative
    {
        return $this->benchmarkRelative;
    }

    /**
     * @param null|BenchmarkRelative $benchmarkRelative
     * @return BenchmarkFunction
     */
    public function setBenchmarkRelative(?BenchmarkRelative $benchmarkRelative): BenchmarkFunction
    {
        $this->benchmarkRelative = $benchmarkRelative;
        return $this;
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        try {
            $this->setResult(
                ($this->callable)(...$this->args)
            );
            return true;
        } catch (\Throwable $e) {
            $this->setException($e);
        }
        return false;
    }
}
