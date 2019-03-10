<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Contracts\CounterValuesInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\SimpleCounterReport;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class ExtendedCounterReportFormatter extends ReportFormatter
{
    /** {@inheritdoc} */
    public function process(ReportInterface $report): string
    {
        if ($report instanceof SimpleCounterReport) {
            if (DEFAULT_NAME === $report->getName()) {
                return $this->simple($report);
            }
            return $this->full($report);
        }
        return '';
    }

    /**
     * @param SimpleCounterReport $report
     * @param bool $eol
     * @return string
     */
    protected function simple(SimpleCounterReport $report, bool $eol = true): string
    {
        /** @var CounterValuesInterface $report */
        return
            sprintf(
                self::COUNTER . ': %s%s',
                (string)$report->getValue(),
                $eol ? PHP_EOL : ''
            );
    }

    /**
     * @param SimpleCounterReport $report
     * @param bool $eol
     * @return string
     */
    protected function full(SimpleCounterReport $report, bool $eol = true): string
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
                $report->getName(),
                (string)$report->getValue(),
                (string)$report->getStep(),
                $this->computeBumped($report),
                (string)$report->getPath(),
                (string)$report->getLength(),
                (string)$report->getMax(),
                (string)$report->getMin(),
                (string)$report->getDiff(),
                $eol ? PHP_EOL : ''
            );
    }

    /**
     * @param SimpleCounterReport $report
     * @return string
     */
    private function computeBumped(SimpleCounterReport $report): string
    {
        return
            sprintf(
                self::FORWARD . '%s ' . self::BACKWARD . '%s',
                $report->getBumped(),
                $report->getBumpedBack()
            );
    }
}
