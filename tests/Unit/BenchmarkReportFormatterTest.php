<?php declare(strict_types=1);


namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Aux\WrongFormattable;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use AlecRabbit\Tools\Reports\ProfilerReport;
use PHPUnit\Framework\TestCase;
use function AlecRabbit\Helpers\getValue;

class BenchmarkReportFormatterTest extends TestCase
{
    public const ITERATIONS = 100;

    /**
     * @test
     * @throws \Exception
     */
    public function wrongReport(): void
    {
        $formatter = new BenchmarkReportFormatter();

        $wrongFormattable = new WrongFormattable();
        $str = $formatter->format($wrongFormattable);
        $this->assertEquals(
            '[AlecRabbit\Tools\Formatters\BenchmarkReportFormatter] ERROR: ' .
            'AlecRabbit\Tools\Reports\BenchmarkReport expected, AlecRabbit\Aux\WrongFormattable given.',
            $str
        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function correctReport(): void
    {
        $formatter = new BenchmarkReportFormatter();
        $benchmark = new Benchmark();
        $benchmarkReport = new BenchmarkReport($formatter, $benchmark);
        $str = $formatter->format($benchmarkReport);
        $this->assertIsString($str);
        $this->assertStringContainsString(Strings::RESULTS, $str);
        $this->assertStringContainsString(Strings::BENCHMARKED, $str);
        $this->assertStringContainsString('0', $str);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function fullBenchmarkProcess(): void
    {
        $bench = new Benchmark(self::ITERATIONS);

        $str_one = 'one';
        $str_two = 'two';

        $comment_one = 'Comment one';
        $comment_two = 'Comment two';

        $str_exception = 'Simulated Exception';
        $with_exception = 'with_exception';

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
                return 2;
            });
        $bench
            ->useName($with_exception)
            ->addFunction(
                function () use ($str_exception) {
                    throw new \RuntimeException($str_exception);
                }
            );
        /** @var BenchmarkReport $report */
        $report = $bench->run()->report();
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $this->assertEquals(self::ITERATIONS * 2, $report->getDoneIterationsCombined());
        $this->assertEquals(self::ITERATIONS * 2, $report->getDoneIterations());
        foreach ($report->getFunctions() as $name => $function) {
            $this->assertInstanceOf(BenchmarkFunction::class, $function);
            $this->assertIsString($name);
            $exception = $function->getException();
            $benchmarkRelative = $function->getBenchmarkRelative();
            $humanReadableName = $function->humanReadableName();
            if ('⟨1⟩ λ' === $name) {
                $this->assertNotNull($benchmarkRelative);
                $this->assertNull($exception);
                $this->assertEquals($str_one, $humanReadableName);
                $this->assertEquals(2, $benchmarkRelative->getRank());
            }
            if ('⟨2⟩ λ' === $name) {
                $this->assertNotNull($benchmarkRelative);
                $this->assertNull($exception);
                $this->assertEquals($str_two, $humanReadableName);
                $this->assertEquals(1, $benchmarkRelative->getRank());
            }
            if ('⟨3⟩ λ' === $name) {
                $this->assertNull($benchmarkRelative);
                $this->assertNotNull($exception);
                $this->assertEquals($with_exception, $humanReadableName);
                $this->assertInstanceOf(\RuntimeException::class, $exception);
                $this->assertEquals($str_exception, $exception->getMessage());
            }
            if ($with_exception === $humanReadableName) {
                $this->assertInstanceOf(\RuntimeException::class, $exception);
                $this->assertEquals($str_exception, $exception->getMessage());
                $this->assertNull($benchmarkRelative);
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
        $this->assertStringNotContainsString('λ', $str);
        $this->assertStringContainsString('Done in', $bench->stat());
        $this->assertStringContainsString('Memory', $bench->stat());
        $this->assertStringContainsString('Real', $bench->stat());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function fullBenchmarkProcessWithReset(): void
    {
        $bench = new Benchmark(self::ITERATIONS);

        $str_one = 'one';
        $str_two = 'two';

        $comment_one = 'Comment one';
        $comment_two = 'Comment two';

        $str_exception = 'Simulated Exception';
        $with_exception = 'with_exception';

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
                return 2;
            });
        $bench
            ->useName($with_exception)
            ->addFunction(
                function () use ($str_exception) {
                    throw new \RuntimeException($str_exception);
                }
            );
        /** @var BenchmarkReport $report */
        $report = $bench->run()->report();
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $this->assertEquals(self::ITERATIONS * 2, $report->getDoneIterationsCombined());
        $this->assertEquals(self::ITERATIONS * 2, $report->getDoneIterations());
        foreach ($report->getFunctions() as $name => $function) {
            $this->assertInstanceOf(BenchmarkFunction::class, $function);
            $this->assertIsString($name);
            $exception = $function->getException();
            $benchmarkRelative = $function->getBenchmarkRelative();
            $humanReadableName = $function->humanReadableName();
            if ('⟨1⟩ λ' === $name) {
                $this->assertNotNull($benchmarkRelative);
                $this->assertNull($exception);
                $this->assertEquals($str_one, $humanReadableName);
                $this->assertEquals(2, $benchmarkRelative->getRank());
            }
            if ('⟨2⟩ λ' === $name) {
                $this->assertNotNull($benchmarkRelative);
                $this->assertNull($exception);
                $this->assertEquals($str_two, $humanReadableName);
                $this->assertEquals(1, $benchmarkRelative->getRank());
            }
            if ('⟨3⟩ λ' === $name) {
                $this->assertNull($benchmarkRelative);
                $this->assertNotNull($exception);
                $this->assertEquals($with_exception, $humanReadableName);
                $this->assertInstanceOf(\RuntimeException::class, $exception);
                $this->assertEquals($str_exception, $exception->getMessage());
            }
            if ($with_exception === $humanReadableName) {
                $this->assertInstanceOf(\RuntimeException::class, $exception);
                $this->assertEquals($str_exception, $exception->getMessage());
                $this->assertNull($benchmarkRelative);
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
        $this->assertStringNotContainsString('λ', $str);
        $this->assertStringContainsString('Done in', $bench->stat());
        $this->assertStringContainsString('Memory', $bench->stat());
        $this->assertStringContainsString('Real', $bench->stat());

        $bench->reset();
        $this->assertTrue($bench->isNotLaunched());
        $this->assertNull(getValue($bench, 'humanReadableName'));
        $this->assertNull(getValue($bench, 'comment'));
        $this->assertSame([], getValue($bench, 'functions'));
        $this->assertSame(1, getValue($bench, 'functionIndex'));
        $this->assertSame(0, getValue($bench, 'doneIterations'));
        $this->assertSame(0, getValue($bench, 'totalIterations'));

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
                return 2;
            });
        $bench
            ->useName($with_exception)
            ->addFunction(
                function () use ($str_exception) {
                    throw new \RuntimeException($str_exception);
                }
            );
        /** @var BenchmarkReport $report */
        $report = $bench->run()->report();
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $this->assertEquals(self::ITERATIONS * 4, $report->getDoneIterationsCombined());
        $this->assertEquals(self::ITERATIONS * 2, $report->getDoneIterations());
        foreach ($report->getFunctions() as $name => $function) {
            $this->assertInstanceOf(BenchmarkFunction::class, $function);
            $this->assertIsString($name);
            $exception = $function->getException();
            $benchmarkRelative = $function->getBenchmarkRelative();
            $humanReadableName = $function->humanReadableName();
            if ('⟨1⟩ λ' === $name) {
                $this->assertNotNull($benchmarkRelative);
                $this->assertNull($exception);
                $this->assertEquals($str_one, $humanReadableName);
                $this->assertEquals(2, $benchmarkRelative->getRank());
            }
            if ('⟨2⟩ λ' === $name) {
                $this->assertNotNull($benchmarkRelative);
                $this->assertNull($exception);
                $this->assertEquals($str_two, $humanReadableName);
                $this->assertEquals(1, $benchmarkRelative->getRank());
            }
            if ('⟨3⟩ λ' === $name) {
                $this->assertNull($benchmarkRelative);
                $this->assertNotNull($exception);
                $this->assertEquals($with_exception, $humanReadableName);
                $this->assertInstanceOf(\RuntimeException::class, $exception);
                $this->assertEquals($str_exception, $exception->getMessage());
            }
            if ($with_exception === $humanReadableName) {
                $this->assertInstanceOf(\RuntimeException::class, $exception);
                $this->assertEquals($str_exception, $exception->getMessage());
                $this->assertNull($benchmarkRelative);
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
        $this->assertStringNotContainsString('λ', $str);
        $this->assertStringContainsString('Done in', $bench->stat());
        $this->assertStringContainsString('Memory', $bench->stat());
        $this->assertStringContainsString('Real', $bench->stat());
    }
}
