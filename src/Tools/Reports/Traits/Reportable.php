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
    /** @var ReportInterface */
    protected $reportObject;

    /**
     * @return ReportInterface
     */
    public function getReport(): ReportInterface
    {
        if (null === $this->reportObject) {
            $this->prepareForReport();
            $this->reportObject = Factory::makeReport($this);
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
