<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Contracts\TimerInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Factory;
use AlecRabbit\Tools\Reports\TimerReport;
use AlecRabbit\Tools\Reports\Traits\HasReport;
use AlecRabbit\Tools\Traits\TimerFields;
use function AlecRabbit\typeOf;

abstract class AbstractTimer implements TimerInterface, ReportableInterface
{
    use TimerFields, HasReport;

    /**
     * Timer constructor.
     * @param null|string $name
     * @param bool $start
     * @throws \Exception
     */
    public function __construct(?string $name = null, bool $start = true)
    {
        $this->checkEnvironment();
        $this->name = $this->defaultName($name);
        $this->creationTime = new \DateTimeImmutable();
        $this->report = (new TimerReport())->buildOn($this);
        if ($start) {
            $this->start();
        }
    }

    protected function checkEnvironment(): void
    {
    }

    /**
     * Starts the timer.
     *
     * @return void
     */
    public function start(): void
    {
        if ($this->isNotStarted()) {
            $this->previous = $this->current();
        }
        $this->started = true;
    }

    abstract public function current();

    /**
     * Marks the time.
     * If timer was not started starts the timer.
     * @param int|null $iterationNumber
     * @return self
     */
    public function check(?int $iterationNumber = null): self
    {
        if ($this->isStopped()) {
            throw new \RuntimeException('Timer[' . $this->name . '] is already stopped.');
        }
        if ($this->isNotStarted()) {
            $this->start();
        } else {
            $this->mark($iterationNumber);
        }
        return $this;
    }

    /**
     * @param int|null $iterationNumber
     */
    protected function mark(?int $iterationNumber = null): void
    {
        $current = $this->current();
        $this->currentValue = $current - $this->previous;
        $this->previous = $current;

        $this->compute($iterationNumber);
    }

    /**
     * @param null|int $iterationNumber
     */
    protected function compute(?int $iterationNumber): void
    {
        if (0 !== $this->count) {
            ++$this->count;
            $this->checkMinValue($iterationNumber);
            $this->checkMaxValue($iterationNumber);
            $this->computeAverage();
        } else {
            $this->initValues();
        }
    }

    /**
     * @param null|int $iterationNumber
     */
    protected function checkMinValue(?int $iterationNumber): void
    {
        if ($this->currentValue < $this->minValue) {
            $this->minValue = $this->currentValue;
            $this->minValueIteration = $iterationNumber ?? $this->count;
        }
    }

    /**
     * @param null|int $iterationNumber
     */
    protected function checkMaxValue(?int $iterationNumber): void
    {
        if ($this->currentValue > $this->maxValue) {
            $this->maxValue = $this->currentValue;
            $this->maxValueIteration = $iterationNumber ?? $this->count;
        }
    }

    protected function computeAverage(): void
    {
        $this->avgValue = (($this->avgValue * ($this->count - 1)) + $this->currentValue) / $this->count;
    }

    protected function initValues(): void
    {
        $this->maxValueIteration = $this->minValueIteration = $this->count = 1;
        $this->maxValue = $this->currentValue;
        $this->minValue = $this->currentValue;
        $this->avgValue = $this->currentValue;
    }

    /**
     * Stops the timer and returns elapsed time string.
     * @return string
     * @throws \Exception
     */
    public function elapsed(): string
    {
//        if ($this->isNotStarted()) { // todo investigate if it's needed here
//            $this->start();
//        }
        if ($this->isNotStopped()) {
            $this->stop();
        }
        return
            $this->formattedElapsed();
    }

    /**
     * Stops the timer.
     * @throws \Exception
     */
    public function stop(): void
    {
        $this->computeElapsed();
        $this->stopped = true;
    }

    /**
     * @throws \Exception
     */
    protected function computeElapsed(): void
    {
        $this->elapsed = (new \DateTimeImmutable())->diff($this->creationTime);
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function formattedElapsed(): string
    {
        return
            Factory::getTimerReportFormatter()::formatElapsed($this->elapsed);
    }

    /**
     * @return \DateInterval
     * @throws \Exception
     */
    public function getElapsed(): \DateInterval
    {
        if ($this->isNotStopped()) {
            $this->computeElapsed();
        }
        return $this->elapsed;
    }

    /**
     * @param int|float $start
     * @param int|float $stop
     * @param null|int $iterationNumber
     * @return self
     */
    public function bounds($start, $stop, ?int $iterationNumber = null): self
    {
        $this->assertStartAndStop($start, $stop);
        if ($this->isNotStarted()) {
            $this->start();
        }
        $this->updateCurrentAndPrevious($start, $stop);

        $this->compute($iterationNumber);
        return $this;
    }

    /**
     * @param int|float $start
     * @param int|float $stop
     */
    protected function assertStartAndStop($start, $stop)
    {
        // todo optimize (make abstract here?)
        $start_ok = false;
        $stop_ok = false;
        if (is_int($start) || is_float($start)) {
            $start_ok = true;
        }
        if (is_int($stop) || is_float($stop)) {
            $stop_ok = true;
        }
        if (!$start_ok) {
            throw new \RuntimeException('Start value is NOT ok. [' . typeOf($start) . ']');
        }
        if (!$stop_ok) {
            throw new \RuntimeException('Stop value is NOT ok. [' . typeOf($stop) . ']');
        }
    }

    /**
     * @param float $start
     * @param float $stop
     */
    protected function updateCurrentAndPrevious($start, $stop): void
    {
        $this->currentValue = $stop - $start;
        $this->previous = $stop;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    protected function prepareForReport(): void
    {
        if ($this->isNotStarted()) {
            $this->start();
            $this->mark();
        }
        $this->stop();
    }
}