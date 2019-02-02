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

class CounterReportFormatter extends Formatter
{
    /** @var CounterReport */
    protected $report;

    /**
     * {@inheritdoc}
     * @throws \Throwable
     */
    public function getString(): string
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
                self::COUNTER . '[%s]: ' . self::VALUE . ': %s, ' . self::STEP . ': %s %s',
                $this->report->getName(),
                (string)$this->report->getValue(),
                (string)$this->report->getStep(),
                $eol ? PHP_EOL : ''
            );
    }
}
