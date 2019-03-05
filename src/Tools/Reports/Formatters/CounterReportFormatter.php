<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:22
 */
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Reports\CounterReport;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class CounterReportFormatter extends OldReportFormatter
{
    /** @var CounterReport */
    protected $report;

    /**
     * {@inheritdoc}
     */
    public function process(): string
    {
        if (DEFAULT_NAME === $this->report->getName()) {
            return $this->simple();
        }
        return $this->full();
    }

    /**
     * @param bool $eol
     * @return string
     */
    public function simple(bool $eol = true): string
    {
        return
            sprintf(
                self::COUNTER . ': %s%s',
                (string)$this->report->getValue(),
                $eol ? PHP_EOL : ''
            );
    }

    /**
     * @param bool $eol
     * @return string
     */
    public function full(bool $eol = true): string
    {
        return
            sprintf(
                self::COUNTER . '[%s]: ' .
                self::VALUE . ': %s, ' .
                self::STEP . ': %s, ' .
                self::BUMPED . ': %s, ' .
                self::PATH . ': %s, ' .
                self::LENGTH . ': %s, ' .
                self::MAX . ': %s, ' .
                self::MIN . ': %s, ' .
                self::DIFF . ': %s %s',
                $this->report->getName(),
                (string)$this->report->getValue(),
                (string)$this->report->getStep(),
                $this->computeBumped(),
                (string)$this->report->getPath(),
                (string)$this->report->getLength(),
                (string)$this->report->getMax(),
                (string)$this->report->getMin(),
                (string)$this->report->getDiff(),
                $eol ? PHP_EOL : ''
            );
    }

    /**
     * @return string
     */
    private function computeBumped(): string
    {
        return sprintf(
            self::FORWARD . '%s ' . self::BACKWARD . '%s',
            $this->report->getBumpedForward(),
            $this->report->getBumpedBack()
        );
    }
}
