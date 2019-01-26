<?php
/**
 * User: alec
 * Date: 03.12.18
 * Time: 22:24
 */

namespace AlecRabbit\Tools\Internal;

use AlecRabbit\Tools\Timer;
use AlecRabbit\Traits\GettableName;
use function AlecRabbit\brackets;
use function AlecRabbit\str_decorate;
use const AlecRabbit\Helpers\Constants\BRACKETS_ANGLE;

/**
 * Class BenchmarkFunction
 * @package AlecRabbit
 * @internal
 */
class BenchmarkFunction
{
    use GettableName;

    /** @var null|string */
    private $comment;

    /** @var int */
    private $index;

    /** @var null|int */
    private $rank;

    /** @var array */
    private $args;

    /** @var callable */
    private $func;

    /** @var mixed */
    private $result;

    /** @var Timer */
    private $timer;

    /** @var \Throwable|null */
    private $exception;

    /** @var null|string  */
    private $humanReadableName;

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
        $this->func = $func;
        $this->comment = $comment;
        $this->name = $name;
        $this->index = $index;
        $this->args = $args;
        $this->timer = new Timer($this->getIndexedName());
        $this->humanReadableName = $humanReadableName;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment ? str_decorate($this->comment, '"') : '';
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
    public function getFunction(): callable
    {
        return $this->func;
    }

    public function enumeratedName(): string
    {
        return
            brackets((string)$this->index, BRACKETS_ANGLE) . ' ' . $this->name;
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
     * @return string
     */
    public function getIndexedName(): string
    {
        return sprintf(
            '⟨%s⟩ %s',
            $this->getIndex(),
            $this->getName()
        );
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
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
    public function getHumanReadableName(): ?string
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
     * @return null|int
     */
    public function getRank(): ?int
    {
        return $this->rank;
    }

    /**
     * @param null|int $rank
     * @return BenchmarkFunction
     */
    public function setRank(?int $rank): BenchmarkFunction
    {
        $this->rank = $rank;
        return $this;
    }


}
