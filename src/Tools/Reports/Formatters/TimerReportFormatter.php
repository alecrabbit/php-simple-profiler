<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\TimerReport;
use Carbon\CarbonInterval;
use function AlecRabbit\typeOf;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class TimerReportFormatter extends ReportFormatter
{
    protected const MILLISECONDS_THRESHOLD = 10000;

    /** {@inheritdoc} */
    public function process(ReportInterface $report): string
    {
        if ($report instanceof TimerReport) {
            if (0 === $report->getCount() && DEFAULT_NAME === $report->getName()) {
                return $this->simple($report);
            }
            return $this->full($report);
        }
        $this->wrongReportType(TimerReport::class, $report);
    }

    /**
     * @param TimerReport $report
     * @param bool $eol
     * @return string
     */
    protected function simple(TimerReport $report, bool $eol = true): string
    {
        return
            sprintf(
                self::ELAPSED . ': %s %s',
                $this->refineElapsed($report->getElapsed()),
                $eol ? PHP_EOL : ''
            );
    }

    /**
     * @param \DateInterval $elapsed
     * @return string
     */
    protected function refineElapsed(\DateInterval $elapsed): string
    {
        return static::formatElapsed($elapsed);
    }

    public static function formatElapsed(\DateInterval $elapsed): string
    {
        $c = CarbonInterval::instance($elapsed);
        if ($c->totalMilliseconds < self::MILLISECONDS_THRESHOLD) {
            return
                Pretty::milliseconds($c->totalMilliseconds);
        }
        return (string)$c;
    }

    /**
     * @param TimerReport $report
     * @param bool $eol
     * @return string
     */
    protected function full(TimerReport $report, bool $eol = true): string
    {
        $r = $report;
        return sprintf(
            self::TIMER . '%s: ' .
            self::AVERAGE . ': %s, ' .
            self::LAST . ': %s, ' .
            self::MIN . '(%s): %s, ' .
            self::MAX . '(%s): %s, ' .
            self::COUNT . ': %s, ' .
            self::ELAPSED . ': %s%s',
            $this->refineName($r->getName()),
            $this->refineSeconds($r->getAverageValue()),
            $this->refineSeconds($r->getLastValue()),
            $r->getMinValueIteration(),
            $this->refineSeconds($r->getMinValue()),
            $r->getMaxValueIteration(),
            $this->refineSeconds($r->getMaxValue()),
            $r->getCount(),
            $this->refineElapsed($r->getElapsed()),
            $eol ? PHP_EOL : ''
        );
    }

    protected function refineName(string $name): string
    {
        if (DEFAULT_NAME === $name) {
            return '';
        }
        return '[' . $name . ']';
    }

    protected function refineSeconds(?float $seconds): string
    {
        return
            $seconds ? Pretty::seconds($seconds) : 'NULL';
    }
}
