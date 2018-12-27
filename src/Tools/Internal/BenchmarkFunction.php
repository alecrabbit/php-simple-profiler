<?php
/**
 * User: alec
 * Date: 03.12.18
 * Time: 22:24
 */

namespace AlecRabbit\Tools\Internal;

use AlecRabbit\Traits\GettableName;
use function AlecRabbit\brackets;
use function AlecRabbit\str_decorate;
use const AlecRabbit\Constants\BRACKETS_ANGLE;

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

    /** @var array */
    private $args;

    /** @var callable */
    private $func;

    /** @var mixed */
    private $result;

    /**
     * BenchmarkedFunction constructor.
     * @param callable $func
     * @param string $name
     * @param int $index
     * @param array $args
     * @param null|string $comment
     */
    public function __construct($func, string $name, int $index, array $args, ?string $comment = null)
    {
        $this->func = $func;
        $this->comment = $comment;
        $this->name = $name;
        $this->index = $index;
        $this->args = $args;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment ? str_decorate($this->comment, '"') : '';
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
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

    public function getEnumeratedName(): string
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
}
