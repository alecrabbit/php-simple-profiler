<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Traits;

use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Factory;

trait Reportable
{
    /** @var ReportInterface|null */
    protected $reportObject;

    /**
     * @param bool $rebuild Rebuild report object
     * @return ReportInterface
     */
    public function getReport(bool $rebuild = true): ReportInterface
    {
        if (null === $this->reportObject || true === $rebuild) {
            $this->prepareForReport();
            $this->reportObject =
                Factory::makeReport(
                    /** @scrutinizer ignore-type */
                    $this
                );
        }
        return
            $this->reportObject;
    }

    /**
     * Makes all necessary actions before report
     */
    protected function prepareForReport(): void
    {
    }
}
