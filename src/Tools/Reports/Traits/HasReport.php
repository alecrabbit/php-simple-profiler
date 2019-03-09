<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Traits;

use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;

trait HasReport
{
    /** @var ReportInterface */
    protected $report;

    /**
     * @param bool $rebuild Rebuild report object
     * @return ReportInterface
     * @throws \Exception
     */
    public function report(bool $rebuild = true): ReportInterface
    {
        $this->meetConditions();
        if (null === $this->report || true === $rebuild) {
            $this->beforeReport();
            /** @var ReportableInterface $that */
            $that = $this;
            $this->report->buildOn($that); // $that used for static analysis
        }
        return
            $this->report;
    }

    /**
     * Makes all necessary actions before report
     */
    protected function beforeReport(): void
    {
    }

    /**
     * Checks if all needed conditions are met
     */
    protected function meetConditions(): void
    {
    }
}
