<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:22
 */
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Reports\CounterReport;
use const AlecRabbit\Constants\Accessories\DEFAULT_NAME;

class CounterReportFormatter extends Formatter
{
    /** @var CounterReport */
    protected $report;

    public function setStyles(): void
    {
    }

    public function getString($colored = true): string
    {
        if (DEFAULT_NAME === $name = $this->report->getName()) {
            return $this->count();
        }
        return $this->full($name);
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
                $this->theme->comment($this->report->getValue()),
                $this->theme->dark($this->report->getStep()),
                PHP_EOL
            );
    }

    /**
     * @param string $name
     * @return string
     * @throws \Throwable
     */
    public function full(string $name): string
    {
        return
            sprintf(
                'Counter:[%s] Value: %s, Step: %s %s',
                $name,
                $this->theme->comment($this->report->getValue()),
                $this->report->getStep(),
                PHP_EOL
            );
    }
}
