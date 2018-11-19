<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:19
 */

namespace AlecRabbit\Profiler;

class Timer implements Contracts\Timer
{
    /** @var string */
    private $name;

    /** @var float */
    private $previous;

    /** @var float */
    private $start;

    /** @var float */
    private $currentValue;

    /** @var float */
    private $avgValue;

    /** @var float */
    private $minValue;

    /** @var float */
    private $maxValue;

    /** @var int */
    private $count;

    /**
     * Timer constructor.
     * @param null|string $name
     */
    public function __construct(?string $name = null)
    {
        $this->name = $name ?? static::_DEFAULT;
    }

    /**
     * Starts the timer.
     * @return Timer
     */
    public function forceStart(): Timer
    {
        $this->start();
        return $this;
    }

    /**
     * Starts the timer.
     *
     * @return void
     */
    public function start(): void
    {
        $this->previous = $this->start = $this->current();
    }

    /**
     * @return float
     */
    private function current(): float
    {
        return
            microtime(true);
    }

    /**
     * @param bool $formatted
     * @return mixed
     */
    public function elapsed(bool $formatted = false)
    {
        if (!$this->start) {
            throw new \RuntimeException('Timer has not been started.');
        }
        $elapsed = $this->current() - $this->start;

        return
            $formatted ? $this->format($elapsed, self::UNIT_MILLISECONDS, 2) : $elapsed;
    }

    /**
     * @param float|null $value
     * @param int|null $units
     * @param int|null $precision
     * @return string
     */
    private function format(?float $value, ?int $units = null, int $precision = null): string
    {
        $units = $units ?? self::UNIT_MILLISECONDS;
        $precision = $precision ?? self::DEFAULT_PRECISION;
        $precision = (int)bounds($precision, 0, 6);
        $value = $value ?? 0.0;
        $suffix = 'ms';
        $coefficient = 1000;

        switch ($units) {
            case self::UNIT_HOURS:
                $suffix = 'h';
                $coefficient = 1 / 3600;
                break;
            case self::UNIT_MINUTES:
                $suffix = 'm';
                $coefficient = 1 / 60;
                break;
            case self::UNIT_SECONDS:
                $suffix = 's';
                $coefficient = 1;
                break;
            case self::UNIT_MILLISECONDS:
                $suffix = 'ms';
                $coefficient = 1000;
                break;
            case self::UNIT_MICROSECONDS:
                $suffix = 'μs';
                $coefficient = 1000000;
                break;
        }
        return
            sprintf(
                '%s%s',
                round($value * $coefficient, $precision),
                $suffix
            );
    }

    /**
     * @param bool|null $formatted
     * @param bool|null $extended
     * @param int|null $units
     * @param int|null $precision
     *
     * @return iterable
     */
    public function report(
        ?bool $formatted = null,
        ?bool $extended = null,
        ?int $units = null,
        ?int $precision = null
    ): iterable {
        if (!$this->count) {
            $this->check();
        }
        $formatted = $formatted ?? false;
        $current = $formatted ? $this->format($this->currentValue, $units, $precision) : $this->currentValue;
        $report = [];
        if ($current) {
            $name = $this->getName();
            $report = [
                static::_NAME => $name,
                static::_LAST => $current,
                static::_EXTENDED => $extended ? $this->getTimerValues($formatted) : null
            ];
        }
        return $report;
    }

    /**
     * Marks the elapsed time.
     * If timer was not started starts the timer.
     */
    public function check(): Timer
    {
        if (null !== $this->previous) {
            $this->mark();
        } else {
            $this->start();
        }
        return $this;
    }

    private function mark(): void
    {
        $current = $this->current();
        $this->currentValue = $current - $this->previous;
        $this->previous = $current;

        if ($this->count) {
            if ($this->currentValue < $this->minValue) {
                $this->minValue = $this->currentValue;
            }
            if ($this->currentValue > $this->maxValue) {
                $this->maxValue = $this->currentValue;
            }
            $this->avgValue = (($this->avgValue * $this->count) + $this->currentValue) / ++$this->count;
        } else {
            $this->count = 1;
            $this->maxValue = $this->currentValue;
            $this->minValue = $this->currentValue;
            $this->avgValue = $this->currentValue;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param bool $formatted
     * @param int|null $units
     * @param int|null $precision
     * @return iterable
     */
    public function getTimerValues(bool $formatted = true, ?int $units = null, ?int $precision = null): iterable
    {
        if (!$count = $this->getCount()) {
            throw new \RuntimeException('Timer has not been started.');
        }
        $minValue = ($count === 1) ? $this->getCurrentValue() : $this->getMinValue();
        return
            $formatted ?
                [
                    static::_LAST => $this->format($this->getCurrentValue(), $units, $precision),
                    static::_AVG => $this->format($this->getAvgValue(), $units, $precision),
                    static::_MIN => $this->format($minValue, $units, $precision),
                    static::_MAX => $this->format($this->getMaxValue(), $units, $precision),
                    static::_COUNT => $count,
                ] :
                [
                    static::_LAST => $this->getCurrentValue(),
                    static::_AVG => $this->getAvgValue(),
                    static::_MIN => $minValue,
                    static::_MAX => $this->getMaxValue(),
                    static::_COUNT => $count,
                ];
    }

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @return float|null
     */
    public function getCurrentValue(): ?float
    {
        return $this->currentValue;
    }

    /**
     * @return float|null
     */
    public function getMinValue(): ?float
    {
        return $this->minValue;
    }

    /**
     * @return float|null
     */
    public function getAvgValue(): ?float
    {
        return $this->avgValue;
    }

    /**
     * @return float|null
     */
    public function getMaxValue(): ?float
    {
        return $this->maxValue;
    }
}
