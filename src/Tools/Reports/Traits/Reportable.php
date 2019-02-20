<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Traits;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Factory;

trait Reportable
{
    /** @var ReportInterface|null */
    protected $reportObject;

    /** @var bool */
    private $launched = false;

    /**
     * @param bool $rebuild Rebuild report object
     * @return ReportInterface
     */
    public function getReport(bool $rebuild = true): ReportInterface
    {
        if ($this instanceof Benchmark && $this->isNotLaunched()) {
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
