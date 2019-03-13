<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use AlecRabbit\Tools\Reports\ExtendedCounterReport;
use AlecRabbit\Tools\Reports\SimpleCounterReport;
use PHPUnit\Framework\TestCase;

class ExtendedCounterReportTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function wrongReportable(): void
    {
        $report = new ExtendedCounterReport();
        $profiler = new Profiler();
        $this->expectException(\RuntimeException::class);
        $report->buildOn($profiler);
    }
}
