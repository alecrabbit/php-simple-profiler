<?php declare(strict_types=1);


namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Reports\ProfilerReport;
use PHPUnit\Framework\TestCase;

class BenchmarkReportFormatterTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function wrongReport(): void
    {
        $formatter = new BenchmarkReportFormatter();
        $profilerReport = new ProfilerReport();
        $this->expectException(\RuntimeException::class);
        $formatter->process($profilerReport);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function correctReport(): void
    {
        $formatter = new BenchmarkReportFormatter();
        $benchmark = new Benchmark();
        $benchmarkReport = new BenchmarkReport();
        $benchmarkReport->buildOn($benchmark);
        $this->assertIsString($formatter->process($benchmarkReport));
    }
}
