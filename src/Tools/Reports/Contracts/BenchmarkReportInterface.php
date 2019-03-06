<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Contracts;

use AlecRabbit\Accessories\MemoryUsage\MemoryUsageReport;
use AlecRabbit\Tools\Internal\BenchmarkFunction;

interface BenchmarkReportInterface
{
    /**
     * @return BenchmarkReportInterface
     */
    public function noReturns(): BenchmarkReportInterface;

    /**
     * @return BenchmarkFunction[]
     */
    public function getFunctions(): array;

    /**
     * @return MemoryUsageReport
     */
    public function getMemoryUsageReport(): MemoryUsageReport;
}
