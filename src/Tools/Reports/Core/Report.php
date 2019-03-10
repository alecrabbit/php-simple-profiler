<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Core;

use AlecRabbit\Tools\AbstractCounter;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use function AlecRabbit\typeOf;

abstract class Report implements ReportInterface
{
    abstract protected static function getFormatter(): FormatterInterface;

    abstract public function buildOn(ReportableInterface $reportable): ReportInterface;

    /** {@inheritdoc} */
    public function __toString(): string
    {
        return
            static::getFormatter()->process($this);
    }

    /**
     * @param string $expected
     * @param ReportableInterface $reportable
     */
    protected function wrongReportable(string $expected, ReportableInterface $reportable): void
    {
        throw new \RuntimeException($expected . ' instance expected ' . typeOf($reportable) . ' given');
    }
}
