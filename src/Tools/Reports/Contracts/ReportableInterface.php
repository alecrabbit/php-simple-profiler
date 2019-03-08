<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Contracts;

interface ReportableInterface
{
    /**
     * @param bool $rebuild Rebuild report object
     * @return ReportInterface
     */
    public function report(bool $rebuild = true): ReportInterface;
}
