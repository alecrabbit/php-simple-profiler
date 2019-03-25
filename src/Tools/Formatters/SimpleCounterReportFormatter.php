<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Formatters;

use AlecRabbit\Tools\Contracts\CounterValuesInterface;
use AlecRabbit\Tools\Formattable;
use AlecRabbit\Tools\Formatters\Core\ReportFormatter;
use AlecRabbit\Tools\Reports\SimpleCounterReport;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class SimpleCounterReportFormatter extends ReportFormatter
{
    /** {@inheritdoc} */
    public function process(Formattable $formattable): string
    {
        if ($formattable instanceof SimpleCounterReport) {
            if (DEFAULT_NAME === $formattable->getName()) {
                return $this->simple($formattable);
            }
            return $this->full($formattable);
        }
        $this->wrongFormattableType(SimpleCounterReport::class, $formattable);
        // @codeCoverageIgnoreStart
        return ''; // never executes
        // @codeCoverageIgnoreEnd
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
                self::BUMPED . ': %s%s',
                $report->getName(),
                (string)$report->getValue(),
                (string)$report->getStep(),
                $this->computeBumped($report),
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
                self::FORWARD . '%s ',
                $report->getBumped()
            );
    }
}
