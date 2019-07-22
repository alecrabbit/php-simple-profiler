<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Traits\GettableName;
use function AlecRabbit\typeOf;

class BenchmarkFunction
{
    use GettableName;

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
        $this->name = $name;
        $this->index = $index;
        $this->args = $args;
        $this->comment = $comment;
        $this->assignedName = $assignName;
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
     * @param mixed $return
     */
    public function setReturn($return): void
    {
        $this->return = $return;
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

}