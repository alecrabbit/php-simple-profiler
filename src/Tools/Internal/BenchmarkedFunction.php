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
 * Class BenchmarkedFunction
 * @package AlecRabbit
 * @internal
 */
class BenchmarkedFunction
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
}
