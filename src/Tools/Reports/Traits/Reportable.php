<?php
/**
 * User: alec
 * Date: 01.12.18
 * Time: 20:25
 */

namespace AlecRabbit\Tools\Reports\Traits;

use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\ReportFactory;

trait Reportable
{

    protected $reportObject;

    /**
     * @return ReportInterface
     */
    public function report(): ReportInterface
    {
        if (null === $this->reportObject) {
            $this->prepareForReport();
            $this->reportObject = ReportFactory::createReport($this);
        }
        return
            $this->reportObject;
    }

    protected function prepareForReport(): void
    {
    }
}
