<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Internal;

use AlecRabbit\Tools\AbstractTimer;
use AlecRabbit\Tools\Formattable;
use AlecRabbit\Tools\HRTimer;
use AlecRabbit\Tools\Timer;
use AlecRabbit\Traits\GettableName;

/**
 * Class BenchmarkFunction
 * @package AlecRabbit
 * @internal
 */
class BenchmarkFunction extends Formattable
{
    use GettableName;

    /** @var bool */
    protected $showReturns = true;
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
    protected $humanReadableName;
    /** @var \Throwable|null */
    protected $exception;
    /** @var AbstractTimer */
    protected $timer;
    /** @var null|BenchmarkRelative */
    protected $benchmarkRelative;
    /** @var bool */
    protected static $forceRegularTimer = false;

    /**
     * BenchmarkFunction constructor.
     *
     * @param callable $func
     * @param string $name
     * @param int $index
     * @param array $args
     * @param null|string $comment
     * @param null|string $humanReadableName
     * @throws \Exception
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
        $this->makeTimer();
    }

    /**
     * @throws \Exception
     */
    protected function makeTimer(): void
    {
        if (PHP_VERSION_ID >= 70300 && false === static::$forceRegularTimer) {
            $this->timer = new HRTimer($this->getIndexedName());
        } else {
            $this->timer = new Timer($this->getIndexedName());
        }
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
     * @param bool $force
     */
    public static function setForceRegularTimer(bool $force): void
    {
        static::$forceRegularTimer = $force;
    }

    /**
     * @return bool
     */
    public static function isForceRegularTimer(): bool
    {
        return self::$forceRegularTimer;
    }

    /**
     * @return string
     */
    public function comment(): string
    {
        return $this->comment ?? '';
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
    }

    /**
     * @return mixed
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @return AbstractTimer
     */
    public function getTimer(): AbstractTimer
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
            $this->setReturn(
                ($this->callable)(...$this->args)
            );
            return true;
        } catch (\Throwable $e) {
            $this->setException($e);
        }
        return false;
    }

    /**
     * @param mixed $return
     */
    public function setReturn($return): void
    {
        $this->return = $return;
    }

    /**
     * @return bool
     */
    public function isNotShowReturns(): bool
    {
        return !$this->isShowReturns();
    }

    /**
     * @return bool
     */
    public function isShowReturns(): bool
    {
        return $this->showReturns;
    }

    /**
     * @param bool $showReturns
     */
    public function setShowReturns(bool $showReturns): void
    {
        $this->showReturns = $showReturns;
    }
}
