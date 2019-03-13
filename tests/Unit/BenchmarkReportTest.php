<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use PHPUnit\Framework\TestCase;

class BenchmarkReportTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function wrongReportable(): void
    {
        $report = new BenchmarkReport();
        $profiler = new Profiler();
        $this->expectException(\RuntimeException::class);
        $report->buildOn($profiler);
    }
}
