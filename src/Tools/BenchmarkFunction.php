<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Traits\GettableName;
use function AlecRabbit\typeOf;

class BenchmarkFunction
{
    use GettableName;

    public const CLOSURE_NAME = 'λ';

    /** @var bool */
    protected $withReturns = false;
    /** @var callable */
    protected $callable;
    /** @var int */
    protected $index;
    /** @var array */
    protected $args;
    /** @var mixed */
    protected $return;
    /** @var null|string */
    protected $comment;
    /** @var null|string */
    protected $assignedName;
    /** @var \Throwable|null */
    protected $exception;

    /** @var null|BenchmarkResult */
    protected $result;
    /** @var null|BenchmarkRelative */
    protected $relative;

    /**
     * BenchmarkFunction constructor.
     * @param callable $func
     * @param array $args
     * @param int $index
     * @param null|string $assignName
     * @param null|string $comment
     */
    public function __construct(
        $func,
        array $args,
        int $index,
        ?string $assignName = null,
        ?string $comment = null
    ) {
//        parent::__construct();
        if (!\is_callable($func, false, $name)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '\'%s\' is NOT callable. Function must be callable. Type of "%s" provided instead.',
                    $name,
                    typeOf($func)
                )
            );
        }
        $this->callable = $func;
        $this->name = $this->refineName($func, $name);
        $this->index = $index;
        $this->args = $args;
        $this->comment = $comment;
        $this->assignedName = $assignName;
    }

    protected function refineName($func, $name): string
    {
        if ($func instanceof \Closure) {
            $name = self::CLOSURE_NAME;
        }
        return $name;
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        try {
            $this->setReturn(
                ($this->callable)(...$this->args)
            );
        } catch (\Throwable $e) {
            $this->setException($e);
            return false;
        }
        return true;
    }

    /**
     * @return callable
     */
    public function getCallable(): callable
    {
        return $this->callable;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return string
     */
    public function getAssignedName(): string
    {
        return $this->assignedName ?? $this->getIndexedName();
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
    public function setException(\Throwable $exception): self
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @return BenchmarkResult|null
     */
    public function getResult(): ?BenchmarkResult
    {
        return $this->result;
    }

    /**
     * @param BenchmarkResult $result
     */
    public function setResult(BenchmarkResult $result): void
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @param mixed $return
     */
    public function setReturn($return): void
    {
        $this->return = $return;
    }

    public function setBenchmarkRelative(BenchmarkRelative $relative): void
    {
        $this->relative = $relative;
    }

    /**
     * @return BenchmarkRelative|null
     */
    public function getRelative(): ?BenchmarkRelative
    {
        return $this->relative;
    }
}
