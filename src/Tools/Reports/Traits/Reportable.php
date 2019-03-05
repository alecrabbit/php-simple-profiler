<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Traits;

use AlecRabbit\Tools\OldBenchmark;
use AlecRabbit\Tools\Reports\Contracts\OldReportInterface;
use AlecRabbit\Tools\Reports\Factory;

trait Reportable
{
    /** @var OldReportInterface|null */
    protected $reportObject;

    /** @var bool */
    private $launched = false;

    /**
     * @param bool $rebuild Rebuild report object
     * @return OldReportInterface
     */
    public function report(bool $rebuild = true): OldReportInterface
    {
        if ($this instanceof OldBenchmark && $this->isNotLaunched()) {
            throw new \RuntimeException('You should launch a benchmark by run() before getting a report');
        }
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
     * @deprecated use report() instead
     * @param bool $rebuild Rebuild report object
     * @return OldReportInterface
     */
    public function getReport(bool $rebuild = true): OldReportInterface
    {
        return $this->report($rebuild);
    }

    /**
     * @return bool
     */
    public function isNotLaunched(): bool
    {
        return !$this->launched;
    }


    /**
     * Makes all necessary actions before report
     */
    protected function prepareForReport(): void
    {
    }
}
