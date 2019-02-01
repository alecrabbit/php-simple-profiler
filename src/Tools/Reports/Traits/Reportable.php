<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Traits;

use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
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
    public function getReport(bool $rebuild = false): ReportInterface
    {
        if (null === $this->reportObject || true === $rebuild) {
            $this->prepareForReport();
            /** @var ReportableInterface $that */
            $that = $this; // for static analyzers
            $this->reportObject = Factory::makeReport($that);
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

    public function resetReportObject(): void
    {
        $this->reportObject = null;
    }
}
