<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use PHPUnit\Framework\TestCase;
use function AlecRabbit\Helpers\getValue;

/**
 * @group time-sensitive
 */
class BenchmarkTest extends TestCase
{
    public const ITERATIONS = 100;

    /** @var Benchmark */
    private $bench;

    /** {@inheritdoc} */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        BenchmarkFunction::setForceRegularTimer(true);
    }

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

//    /**
//     * @test
//     * @throws \Exception
//     */
//    public function reportWithCircularReferencesReturn(): void
//    {
//        $this->bench = new Benchmark(100);
//        $var = 2;
//
//        $comment = 'Added First(1)';
//        $this->bench
//            ->withComment($comment)
//            ->addFunction(
//                function () use ($var) {
//                    usleep(10);
//                    return $var;
//                }
//            );
//        $this->bench
//            ->withComment($comment)
//            ->addFunction(
//                function () use ($var) {
//                    usleep(10);
//                    return $var;
//                }
//            );
//        $report = $this->bench->report();
//        $this->assertInstanceOf(BenchmarkReport::class, $report);
//        $str = (string)$report;
//        $this->assertIsString($str);
//        $this->assertStringNotContainsString(__CLASS__, $str);
//        $this->assertStringContainsString($comment, $str);
//    }

    /**
     * @test
     * @throws \Exception
     */
    public function addFunctionWithComment(): void
    {
        $name1 = 'NameFirst';
        $name2 = 'NameSecond';
        $name3 = 'NameThird';
        $name4 = 'NameFours';
        $comment1 = 'Comment First';
        $comment2 = 'Comment Second';
        $comment3 = 'Comment Third';
        $comment4 = 'Comment Fours';
        $this->bench
            ->useName($name4)
            ->withComment($comment4)
            ->add(
                function ($a) {
                    usleep(40000);
                    return $a;
                },
                4
            );
        $this->bench
            ->useName($name2)
            ->withComment($comment2)
            ->add(
                function ($a) {
                    usleep(200);
                    return $a;
                },
                2
            );
        $this->bench
            ->useName($name1)
            ->withComment($comment1)
            ->add(
                function ($a) {
                    usleep(10);
                    return $a;
                },
                1
            );
        $this->bench
            ->useName($name3)
            ->withComment($comment3)
            ->add(
                function ($a) {
                    usleep(3000);
                    return $a;
                },
                3
            );
        /** @var BenchmarkReport $report */
        $report = $this->bench->report();
        dump($report);
        dump((string)$report);
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $this->assertEquals(self::ITERATIONS * 4, $report->getDoneIterationsCombined());
        /** @var BenchmarkFunction $function */
        foreach ($report->getFunctions() as $function) {
            $comment = $function->comment();
            $return = $function->getReturn();
            $rank = $function->getBenchmarkRelative()->getRank();
            $this->assertSame($return, $rank);
            $this->assertIsString($comment);
            $var = 'comment' . $rank;
            $this->assertSame($comment, $$var);
            $var = 'name' . $rank;
            $this->assertSame($function->humanReadableName(), $$var);
        }
    }

//    /**
//     * @test
//     * @throws \Exception
//     */
//    public function addFunctionWithException(): void
//    {
//        $str_one = 'one';
//        $str_two = 'two';
//        $with_exception = 'with_exception';
//
//        $comment = 'some_comment';
//        $str_exception = 'Simulated Exception';
//
//        $this->bench
//            ->useName($str_one)
//            ->addFunction(function () {
//                usleep(10);
//                return 1;
//            });
//        $this->bench
//            ->useName($str_two)
//            ->addFunction(function () {
//                usleep(100);
//                return 2;
//            });
//        $this->bench
//            ->withComment($comment)
//            ->addFunction(function () {
//                usleep(200);
//                return 3;
//            });
//        $this->bench
//            ->useName($with_exception)
//            ->addFunction(
//                function () use ($str_exception) {
//                    throw new \RuntimeException($str_exception);
//                }
//            );
//
//        $this->bench->showProgressBy(
//            function () {
//            },
//            function () {
//            },
//            function () {
//            }
//        );
//        /** @var BenchmarkReport $report */
//        $report = $this->bench->report();
//        $this->assertInstanceOf(BenchmarkReport::class, $report);
//        /** @var BenchmarkFunction $function */
//        foreach ($report->getFunctions() as $function) {
//            $exception = $function->getException();
//            $benchmarkRelative = $function->getBenchmarkRelative();
//            if ($with_exception === $function->humanReadableName()) {
//                $this->assertInstanceOf(\RuntimeException::class, $exception);
//                $this->assertEquals($str_exception, $exception->getMessage());
//                $this->assertNull($benchmarkRelative);
//            } else {
//                $this->assertInstanceOf(BenchmarkRelative::class, $benchmarkRelative);
//                $this->assertNull($exception);
//                $rank = $function->getBenchmarkRelative()->getRank();
//                $return = $function->getReturn();
//                $this->assertSame($rank, $return);
//            }
//        }
//    }

    /**
     * @test
     * @throws \Exception
     */
    public function fullBenchmarkProcess(): void
    {
        $iterations = 100;
        $bench = new Benchmark($iterations);

        $str_one = 'one';
        $str_two = 'two';
        $str_exception = 'Simulated Exception';
        $with_exception = 'with_exception';

        $bench
            ->useName($str_one)
            ->add(function () {
                usleep(100);
                return 1;
            });
        $bench
            ->useName($str_two)
            ->add(function () {
                usleep(10);
                return 2;
            });
        $bench
            ->useName($with_exception)
            ->add(
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
            }
            if ($with_exception === $humanReadableName) {
                $this->assertInstanceOf(\RuntimeException::class, $exception);
                $this->assertNull($benchmarkRelative);
                $this->assertEquals($str_exception, $exception->getMessage());
            }
        }
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
            ->add(function () {
                usleep(100);
                return 1;
            });
        $bench
            ->useName($str_two)
            ->add(function () {
                usleep(10);
                return 2;
            });
        $bench
            ->add(
                function () use ($str_exception) {
                    throw new \RuntimeException($str_exception);
                }
            );
        /** @var BenchmarkReport $report */
        $report = $bench->run()->showReturns()->report();
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
            ->add(function () {
                usleep(100);
                return 1;
            });
        $this->bench
            ->useName($str_two)
            ->add(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->withComment($comment)
            ->add(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->add(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->add(
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
        $this->assertTrue($this->bench->isNotLaunched());
        $this->assertNull(getValue($this->bench, 'humanReadableName'));
        $this->assertNull(getValue($this->bench, 'comment'));
        $this->assertSame([], getValue($this->bench, 'functions'));
        $this->assertSame(1, getValue($this->bench, 'functionIndex'));
        $this->assertSame(0, getValue($this->bench, 'doneIterations'));
        $this->assertSame(0, getValue($this->bench, 'totalIterations'));

        $this->bench
            ->useName($str_one)
            ->add(function () {
                usleep(100);
                return 1;
            });
        $this->bench
            ->useName($str_two)
            ->add(function () {
                usleep(10);
                return [];
            });
        $this->bench
            ->withComment($comment)
            ->add(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->add(function () {
                usleep(10);
                return 2;
            });
        $this->bench
            ->add(
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

    /**
     * @test
     * @throws \Exception
     */
    public function wrongArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->bench
            ->withComment('Added Third(2)')
            ->add('notCallable');
    }

    /**
     * @test
     * @throws \Exception
     */
    public function minIterations(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('[' . Benchmark::class . '] Number of Iterations should be greater than 100.');
        $b = new Benchmark(10);
        $b->run();
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->bench = new Benchmark(self::ITERATIONS);
    }
}
