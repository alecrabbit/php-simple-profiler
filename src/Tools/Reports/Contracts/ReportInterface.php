<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Contracts;

interface ReportInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @param ReportableInterface $reportable
     * @return ReportInterface
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function buildOn(ReportableInterface $reportable): ReportInterface;
}
