<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:22
 */
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Tools\Reports\TimerReport;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class TimerReportFormatter extends OldReportFormatter
{
    /** @var TimerReport */
    protected $report;

    /**
     * @return string
     */
    public function process(): string
    {
        if (0 === $this->report->getCount() && DEFAULT_NAME === $this->report->getName()) {
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
                self::ELAPSED . ': %s %s',
                $this->ftime($this->report->getElapsed()),
                $eol ? PHP_EOL : ''
            );
    }

    protected function ftime(float $seconds): string
    {
        return Pretty::seconds($seconds);
    }

    /**
     * @param bool $eol
     * @return string
     */
    public function full(bool $eol = true): string
    {
        $r = $this->report;
        return sprintf(
            self::TIMER . '%s: ' .
            self::AVERAGE . ': %s, ' .
            self::LAST . ': %s, ' .
            self::MIN . '(%s): %s, ' .
            self::MAX . '(%s): %s, ' .
            self::COUNT . ': %s, ' .
            self::ELAPSED . ': %s%s',
            $this->fname($r->getName()),
            $this->ftime($r->getAverageValue()),
            $this->ftime($r->getLastValue()),
            $r->getMinValueIteration(),
            $this->ftime($r->getMinValue()),
            $r->getMaxValueIteration(),
            $this->ftime($r->getMaxValue()),
            $r->getCount(),
            $this->ftime($r->getElapsed()),
            $eol ? PHP_EOL : ''
        );
    }

    protected function fname(string $name): string
    {
        if (DEFAULT_NAME === $name) {
            return '';
        }
        return '[' . $name . ']';
    }
}
