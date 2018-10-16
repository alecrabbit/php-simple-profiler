<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:19
 */

namespace AlecRabbit\Profiler;


/**
 * Class Timer
 * @package AlecRabbit\Profiler
 */
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
    public function forceStart()
    {
        $this->start();
        return $this;
    }

    /**
     * Starts the timer.
     *
     * @return void
     */
    public function start()
    {
        $this->previous = $this->start = $this->current();
    }

    private function current()
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
        if (!$this->start)
            throw new \RuntimeException('Timer has not been started.');
        $elapsed = $this->current() - $this->start;

        return
            $formatted ? $this->format($elapsed, self::UNIT_MILLISECONDS, 2) : $elapsed;
    }

    private function format(?float $value, ?int $units = null, int $precision = null)
    {
        $units = $units ?? self::UNIT_MILLISECONDS;
        $precision = $precision ?? self::DEFAULT_PRECISION;
        $precision = bounds($precision, 0, 6);

        if ($value === null)
            return null;
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
            default:
                $suffix = 'ms';
                $coefficient = 1000;
                break;
        }
        return
            sprintf('%s%s',
                round($value * $coefficient, $precision),
                $suffix
            );
    }

    public function report(?bool $formatted = null, ?bool $extended = null, ?int $units = null, ?int $precision = null): iterable
    {
        if (!$this->count)
            $this->check();
        $formatted = $formatted ?? false;
        $current = $formatted ? $this->format($this->currentValue, $units, $precision) : $this->currentValue;
        $report = [];
        if ($current) {
            $name = $this->getName();
            $report[$name] = [
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
        if (isset($this->previous)) {
            $this->mark();
        } else {
            $this->start();
        }
        return $this;
    }

    private function mark()
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
        $minValue = ($count == 1) ? $this->getCurrentValue() : $this->getMinValue();
        return [
            static::_LAST =>
                $formatted ?
                    $this->format($this->getCurrentValue(), $units, $precision) :
                    $this->getCurrentValue(),
            static::_AVG =>
                $formatted ?
                    $this->format($this->getAvgValue(), $units, $precision) :
                    $this->getAvgValue(),
            static::_MIN =>
                $formatted ?
                    $this->format($minValue, $units, $precision) :
                    $minValue,
            static::_MAX =>
                $formatted ?
                    $this->format($this->getMaxValue(), $units, $precision) :
                    $this->getMaxValue(),
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