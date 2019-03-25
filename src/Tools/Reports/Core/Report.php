<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Core;

use AlecRabbit\Tools\Formattable;
use AlecRabbit\Tools\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use function AlecRabbit\typeOf;

abstract class Report extends Formattable implements ReportInterface
{
    abstract public function buildOn(ReportableInterface $reportable): ReportInterface;

    /** {@inheritdoc} */
    public function __toString(): string
    {
        return
            static::getFormatter()->process($this);
    }

    abstract protected static function getFormatter(): FormatterInterface;

    /**
     * @param string $expected
     * @param ReportableInterface $reportable
     */
    protected function wrongReportable(string $expected, ReportableInterface $reportable): void
    {
        throw new \RuntimeException(
            'Instance of [' . $expected . '] expected, [' . typeOf($reportable) . '] given'
        );
    }
}
