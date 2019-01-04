<?php
/**
 * User: alec
 * Date: 01.12.18
 * Time: 20:25
 */

namespace AlecRabbit\Tools\Reports\Traits;

use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Factory;

trait Reportable
{
    /** @var ReportInterface|null */
    protected $reportObject;

    /**
     * @param bool $rebuild
     * @return ReportInterface
     */
    public function getReport($rebuild = false): ReportInterface
    {
        if (null === $this->reportObject || true === $rebuild) {
            $this->prepareForReport();
            /** @var \AlecRabbit\Tools\Reports\Contracts\ReportableInterface $that */
            $that = $this; // for static analyzers
            $this->reportObject = Factory::makeReport($that);
        }
        return
            $this->reportObject;
    }

    public function resetReportObject(): void
    {
        $this->reportObject = null;
    }

    /**
     * Makes all necessary actions before report
     */
    protected function prepareForReport(): void
    {
    }
}
