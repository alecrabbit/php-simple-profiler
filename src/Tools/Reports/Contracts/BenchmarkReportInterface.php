<?php declare(strict_types=1);


namespace AlecRabbit\Tools\Reports\Contracts;

interface BenchmarkReportInterface
{
    /**
     * @return BenchmarkReportInterface
     */
    public function noReturns(): BenchmarkReportInterface;
}
