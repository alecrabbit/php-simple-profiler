<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 15:46
 */

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class BenchmarkTest extends TestCase
{
    /** @var Benchmark */
    private $bench;

    /**
     * @test
     * @throws \Exception
     */
    public function instance(): void
    {
        $this->assertInstanceOf(Benchmark::class, $this->bench);
        $this->assertInstanceOf(BenchmarkReport::class, $this->bench->run()->report());
        $b = new Benchmark();
        $this->assertInstanceOf(BenchmarkReport::class, $b->report());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function addFunctionWithComment(): void
    {
        $this->bench
            ->withComment('Added First(1)')
            ->addFunction(function () {
                usleep(498);
            }, 1, 2);
        $this->bench
            ->withComment('Added Second(3)')
            ->addFunction(function () {
                usleep(549);
            });
        $this->bench
            ->withComment('Added Third(2)')
            ->addFunction(function () {
                usleep(521);
            });
        $this->bench
            ->withComment('Added Fours(2)')
            ->addFunction(function () {
                usleep(521);
            });
        $report = $this->bench->report();
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $str = (string)$report;
        // todo improve test
        // todo move strings check to formatter test
        // todo check rank here etc.
        $this->assertIsString($str);
        $this->assertStringContainsString('Added First(1)', $str);
        $this->assertStringContainsString('Added Second(3)', $str);
        $this->assertStringContainsString('Added Third(2)', $str);
        $this->assertStringContainsString('Added Fours(2)', $str);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function reportWithCircularReferencesReturn(): void
    {
        $this->bench = new Benchmark(100);
        $var = 2;

        $comment = 'Added First(1)';
        $this->bench
            ->withComment($comment)
            ->addFunction(
                function () use ($var) {
                    usleep(10);
                    return $var;
                }
            );
        $this->bench
            ->withComment($comment)
            ->addFunction(
                function () use ($var) {
                    usleep(10);
                    return $var;
                }
            );
        $report = $this->bench->report();
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringNotContainsString(__CLASS__, $str);
        $this->assertStringContainsString($comment, $str);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function addFunctionWithException(): void
    {
        $str_one = 'one';
        $str_two = 'two';

        $comment = 'some_comment';
        $str_exception = 'Simulated Exception';

        $this->bench
            ->useName($str_one)
            ->addFunction(function () {
                usleep(100);
                return 1;
            });
        $this->bench
            ->useName($str_two)
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->withComment($comment)
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->addFunction(
                function () use ($str_exception) {
                    throw new \RuntimeException($str_exception);
                }
            );

        $this->bench->showProgressBy(
            function () {
            },
            function () {
            },
            function () {
            }
        );
        $report = $this->bench->report();
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringContainsString('Done in', $this->bench->stat());
        $this->assertStringContainsString('Memory', $this->bench->stat());
        $this->assertStringContainsString('Real', $this->bench->stat());

        $this->assertStringContainsString($str_exception, $str);
        $this->assertStringContainsString(\RuntimeException::class, $str);
        $this->assertStringContainsString($str_one, $str);
        $this->assertStringContainsString($str_two, $str);
        $this->assertStringContainsString($comment, $str);
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
        $str_exception = 'Simulated Exception';

        $bench
            ->useName($str_one)
            ->addFunction(function () {
                usleep(100);
                return 1;
            });
        $bench
            ->useName($str_two)
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $bench
            ->addFunction(
                function () use ($str_exception) {
                    throw new \RuntimeException($str_exception);
                }
            );
        /** @var BenchmarkReport $report */
        $report = $bench->run()->report();
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
                $this->assertEquals($str_exception, $exception->getMessage());
            }
        }
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringContainsString($str_one, $str);
        $this->assertStringContainsString($str_two, $str);
        $this->assertStringContainsString($str_exception, $str);
        $this->assertStringContainsString(\RuntimeException::class, $str);
        $this->assertStringContainsString('λ', $str);
        $this->assertStringContainsString('Done in', $bench->stat());
        $this->assertStringContainsString('Memory', $bench->stat());
        $this->assertStringContainsString('Real', $bench->stat());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function fullBenchmarkProcessNoReturns(): void
    {
        // this test is heavily hardcoded
        $iterations = 100;
        $bench = new Benchmark($iterations);

        $str_one = 'one';
        $str_two = 'two';
        $str_exception = 'Simulated Exception';

        $bench
            ->useName($str_one)
            ->addFunction(function () {
                usleep(100);
                return 1;
            });
        $bench
            ->useName($str_two)
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $bench
            ->addFunction(
                function () use ($str_exception) {
                    throw new \RuntimeException($str_exception);
                }
            );
        /** @var BenchmarkReport $report */
        $report = $bench->run()->report();
        $report->showReturns();
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
                $this->assertEquals($str_exception, $exception->getMessage());
            }
        }
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringContainsString($str_one, $str);
        $this->assertStringContainsString($str_two, $str);
        $this->assertStringContainsString($str_exception, $str);
        $this->assertStringContainsString(\RuntimeException::class, $str);
        $this->assertStringContainsString('λ', $str);
        $this->assertStringContainsString('integer(1)', $str);
        $this->assertStringContainsString('integer(2)', $str);
        $this->assertStringContainsString('Done in', $bench->stat());
        $this->assertStringContainsString('Memory', $bench->stat());
        $this->assertStringContainsString('Real', $bench->stat());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function addFunctionWithReset(): void
    {
        $str_one = 'one';
        $str_two = 'two';
        $comment = 'some_comment';
        $str_exception = 'Simulated Exception';

        $this->bench
            ->useName($str_one)
            ->addFunction(function () {
                usleep(100);
                return 1;
            });
        $this->bench
            ->useName($str_two)
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->withComment($comment)
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->addFunction(
                function () use ($str_exception) {
                    throw new \RuntimeException($str_exception);
                }
            );

        $this->bench->showProgressBy(
            function () {
            },
            function () {
            },
            function () {
            }
        );
        /** @var BenchmarkReport $report */
        $report = $this->bench->run()->report();
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringContainsString('Done in', $this->bench->stat());
        $this->assertStringContainsString('Memory', $this->bench->stat());
        $this->assertStringContainsString('Real', $this->bench->stat());

        $this->assertStringContainsString($str_one, $str);
        $this->assertStringContainsString($str_two, $str);
        $this->assertStringContainsString($comment, $str);
        $this->assertStringContainsString(\RuntimeException::class, $str);
        $this->assertEquals(400, $report->getDoneIterationsCombined());
        $this->assertEquals(400, $report->getDoneIterations());

        $this->bench->reset();
        $this->bench
            ->useName($str_one)
            ->addFunction(function () {
                usleep(100);
                return 1;
            });
        $this->bench
            ->useName($str_two)
            ->addFunction(function () {
                usleep(10);
                return [];
            });
        $this->bench
            ->withComment($comment)
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->addFunction(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->addFunction(
                function () use ($str_exception) {
                    throw new \RuntimeException($str_exception);
                }
            );
        /** @var BenchmarkReport $report */
        $report = $this->bench->run()->report();
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringContainsString('Done in', $this->bench->stat());
        $this->assertStringContainsString('Memory', $this->bench->stat());
        $this->assertStringContainsString('Real', $this->bench->stat());
        $this->assertStringContainsString($str_one, $str);
        $this->assertStringContainsString($str_two, $str);
        $this->assertStringContainsString($comment, $str);
        $this->assertStringContainsString(\RuntimeException::class, $str);
        $this->assertEquals(800, $report->getDoneIterationsCombined());
        $this->assertEquals(400, $report->getDoneIterations());
    }

    /** @test */
    public function wrongArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->bench
            ->withComment('Added Third(2)')
            ->addFunction('notCallable');
    }

    /**
     * @test
     * @throws \Exception
     */
    public function minIterations(): void
    {
        $this->expectException(\RuntimeException::class);
        $b = new Benchmark(10);
        $b->run();
    }

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->bench = new Benchmark(100);
    }
}
