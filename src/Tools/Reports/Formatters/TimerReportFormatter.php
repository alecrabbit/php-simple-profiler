<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:22
 */
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Reports\TimerReport;
use function AlecRabbit\format_time_auto;
use const AlecRabbit\Constants\Accessories\DEFAULT_NAME;

class TimerReportFormatter extends Formatter
{
    /** @var TimerReport */
    protected $report;

    public function setStyles(): void
    {
    }

    public function getString(): string
    {
        if (DEFAULT_NAME === $name = $this->report->getName()) {
            return $this->elapsed();
        }
        return $this->full($name);
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function elapsed(): string
    {
        return
            sprintf(
                'Elapsed: %s %s',
                $this->theme->comment(format_time_auto($this->report->getElapsed())),
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
        try {
            $str = sprintf(
                'Timer:[%s] Average: %s, Last: %s, Min(%s): %s, Max(%s): %s, Count: %s' . PHP_EOL,
                $this->theme->info($name),
                $this->theme->comment(format_time_auto($this->report->getAverageValue())),
                format_time_auto($this->report->getLastValue()),
                $this->report->getMinValueIteration(),
                format_time_auto($this->report->getMinValue()),
                $this->report->getMaxValueIteration(),
                format_time_auto($this->report->getMaxValue()),
                $this->report->getCount()
            );
        } catch (\Throwable $e) {
            $str =
                sprintf(
                    'Timer:[%s] %s' . PHP_EOL,
                    $this->theme->info($name),
                    $this->theme->red('Exception encountered')
                );
        }
        return $str;
    }
}
