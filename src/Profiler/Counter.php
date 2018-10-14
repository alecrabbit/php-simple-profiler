<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:18
 */

namespace AlecRabbit\Profiler;


use AlecRabbit\Exception\RuntimeException;

class Counter implements Contracts\Counter
{
    const REPORT_FORMAT = '%s %s';

    /** @var string */
    private $name;

    /** @var int */
    private $value;

    /** @var int */
    private $step;

    /**
     * Counter constructor
     * @param string|null $name
     * @param int $value
     */
    public function __construct(?string $name = null, int $value = 0)
    {
        $this->name = $name ?? 'default';
        $this->value = $value;
        $this->step = 1;
    }

    public function bump(): int
    {
        return
            $this->bumpUp();
    }

    public function bumpUp(): int
    {
        $this->value += $this->step;
        return
            $this->value;
    }

    public function bumpWith(int $step, bool $setStep = false): int
    {
        $this->value += $this->checkStep($step);
        if ($setStep)
            $this->setStep($step);
        return
            $this->value;
    }

    /**
     * @param int $step
     * @return Counter
     */
    public function setStep(int $step): Counter
    {
        $this->step = $this->checkStep($step);
        return $this;
    }

    private function checkStep(int $step): int
    {
        if ($step == 0)
            throw new RuntimeException('Counter step should be non-zero integer.');
        return $step;
    }

    public function bumpDown(): int
    {
        $this->value -= $this->step;
        return
            $this->value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function report(bool $extended = false): string
    {
        return
            sprintf(
                static::REPORT_FORMAT,
                $this->getName(),
                $this->value
            );

    }

    public function getName(): string
    {
        return $this->name;
    }
}