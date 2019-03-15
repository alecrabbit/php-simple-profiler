<?php declare(strict_types=1);


namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
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

    /**
     * @test
     * @throws \Exception
     */
    public function fullBenchmarkProcess(): void
    {
        // this test is heavily hardcoded
        $iterations = 100;
        $bench = new Benchmark($iterations);

        $str_one = 'one';
        $str_two = 'two';
        $comment_one = 'Comment one';
        $comment_two = 'Comment two';
        $str_exception = 'Simulated Exception';

        $bench
            ->useName($str_one)
            ->withComment($comment_one)
            ->addFunction(function () {
                usleep(100);
                return 1;
            });
        $bench
            ->useName($str_two)
            ->withComment($comment_two)
            ->addFunction(function () {
                usleep(10);
                return 1;
            });
        $bench
            ->addFunction(
                function () use ($str_exception) {
                    throw new \RuntimeException($str_exception);
                }
            );
        /** @var BenchmarkReport $report */
        $report = $bench->showReturns()->report();
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $this->assertEquals($iterations * 2, $report->getDoneIterationsCombined());
        $this->assertEquals($iterations * 2, $report->getDoneIterations());
        foreach ($report->getFunctions() as $name => $function) {
            $this->assertInstanceOf(BenchmarkFunction::class, $function);
            $this->assertIsString($name);
            $exception = $function->getException();
            $benchmarkRelative = $function->getBenchmarkRelative();
            if ('⟨2⟩ λ' === $name) {
                $this->assertNotNull($benchmarkRelative);
                $this->assertNull($exception);
                $this->assertEquals(1, $benchmarkRelative->getRank());
            }
            if ('⟨1⟩ λ' === $name) {
                $this->assertNotNull($benchmarkRelative);
                $this->assertNull($exception);
                $this->assertEquals(2, $benchmarkRelative->getRank());
            }
            if ('⟨3⟩ λ' === $name) {
                $this->assertNull($benchmarkRelative);
                $this->assertNotNull($exception);
                $this->assertInstanceOf(\RuntimeException::class, $exception);
                $this->assertEquals($str_exception, $exception->getMessage());
            }
        }
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringContainsString($str_one, $str);
        $this->assertStringContainsString($str_two, $str);
        $this->assertStringContainsString($comment_one, $str);
        $this->assertStringContainsString($comment_two, $str);
        $this->assertStringContainsString($str_exception, $str);
        $this->assertStringContainsString(\RuntimeException::class, $str);
        $this->assertStringContainsString('λ', $str);
        $this->assertStringContainsString('Done in', $bench->stat());
        $this->assertStringContainsString('Memory', $bench->stat());
        $this->assertStringContainsString('Real', $bench->stat());
    }
}
