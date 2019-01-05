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
use const AlecRabbit\Constants\Traits\DEFAULT_NAME;

class TimerReportFormatter extends Formatter
{
    /** @var TimerReport */
    protected $report;

    /** {@inheritdoc} */
    public function setStyles(): void
    {
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function getString(): string
    {
        if (DEFAULT_NAME === $this->report->getName()) {
            return $this->elapsed();
        }
        return $this->full();
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
                $this->themed->comment(format_time_auto($this->report->getElapsed())),
                PHP_EOL
            );
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function full(): string
    {
        $name =  $this->report->getName();
        try {
            $str = sprintf(
                'Timer[%s]: Average: %s, Last: %s, Min(%s): %s, Max(%s): %s, Count: %s' . PHP_EOL,
                $this->themed->info($name),
                $this->themed->comment(format_time_auto($this->report->getAverageValue())),
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
                    'Timer[%s]: %s' . PHP_EOL,
                    $this->themed->red($name),
                    $this->themed->comment('Exception encountered')
                );
        }
        return $str;
    }
}
