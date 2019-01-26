<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:22
 */
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Reports\CounterReport;
use const \AlecRabbit\Traits\Constants\DEFAULT_NAME;

class CounterReportFormatter extends Formatter
{
    /** @var CounterReport */
    protected $report;

    /** {@inheritdoc} */
    public function setStyles(): void
    {
    }

    /**
     * {@inheritdoc}
     * @throws \Throwable
     */
    public function getString(): string
    {
        if (DEFAULT_NAME === $this->report->getName()) {
            return $this->count();
        }
        return $this->full();
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function count(): string
    {
        return
            sprintf(
                'Counter: %s(%s)%s',
                $this->themed->comment((string)$this->report->getValue()),
                $this->themed->dark((string)$this->report->getStep()),
                PHP_EOL
            );
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function full(): string
    {
        return
            sprintf(
                'Counter[%s]: Value: %s, Step: %s %s',
                $this->themed->info($this->report->getName()),
                $this->themed->comment((string)$this->report->getValue()),
                $this->themed->dark((string)$this->report->getStep()),
                PHP_EOL
            );
    }
}
