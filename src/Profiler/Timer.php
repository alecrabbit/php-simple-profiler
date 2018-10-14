<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:19
 */

namespace AlecRabbit\Profiler;


use AlecRabbit\Profiler\Contracts\Report;

class Timer implements Contracts\Timer
{
    /** @var string */
    private $name;

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
     * All of the current timers.
     *
     * @var array
     */
    public $timers = [];

    /**
     * Timer constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Starts the timer.
     *
     * @return void
     */
    public function start()
    {
        $this->start = microtime(true);
    }

    /**
     * Marks the elapsed time.
     * If timer was not started starts the timer.
     */
    public function check(): Timer
    {
        if (isset($this->start)) {
            $this->mark();
        } else {
            $this->start();
        }
        return $this;
    }

    private function mark()
    {
        $currentTime = microtime(true);

        $this->currentValue = $currentTime - $this->start;

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
     * @return float|null
     */
    public function getCurrentValue(): ?float
    {
        return $this->currentValue;
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
    public function getMinValue(): ?float
    {
        return $this->minValue;
    }

    /**
     * @return float|null
     */
    public function getMaxValue(): ?float
    {
        return $this->maxValue;
    }

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function elapsed(): float
    {
        return
            $this->currentValue;
    }

    /**
     * @param  bool $extended
     * @param int $units
     * @param  int $precision
     * @return string
     */
    public function report(bool $extended = false, int $units = self::UNIT_MILLISECONDS, int $precision = 2): string
    {
        $current = $this->format($this->currentValue, $units, $precision);
        $r = '';
        if ($current) {
            $r .= sprintf(
                Report::REPORT_FORMAT,
                $this->getName(),
                $current
            );
            if ($extended) {
                $values = $this->getTimerValues(true);
                $r .= Report::REPORT_DIV;
                foreach ($values as $key => $value) {
//                    if ($value)
                    $r .=
                        sprintf(
                            Report::REPORT_EXTENDED_SUFFIX,
                            $key,
                            $value
                        );
                }
                $r .= PHP_EOL;
            }
        }
        return $r;
    }

    private function format(?float $value, int $units, int $precision)
    {
        if ($value === null)
            return null;
        switch ($units) {
            case self::UNIT_HOURS :
                $suffix = 'h';
                $coefficient = 1 / 3600000000;
                break;
            case self::UNIT_MINUTES:
                $suffix = 'm';
                $coefficient = 1 / 60000000;
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

    public function getTimerValues(bool $formatted = true, int $units = self::UNIT_MILLISECONDS, int $precision = 3): array
    {
        $count = $this->getCount();
        $minValue = ($count == 1) ? $this->getCurrentValue() : $this->getMinValue();
        return [
            'Last' =>
                $formatted ?
                    $this->format($this->getCurrentValue(), $units, $precision) :
                    $this->getCurrentValue(),
            'Avg' =>
                $formatted ?
                    $this->format($this->getAvgValue(), $units, $precision) :
                    $this->getAvgValue(),
            'Min' =>
                $formatted ?
                    $this->format($minValue, $units, $precision) :
                    $minValue,
            'Max' =>
                $formatted ?
                    $this->format($this->getMaxValue(), $units, $precision) :
                    $this->getMaxValue(),
            'Count' => $count,
        ];

    }

}