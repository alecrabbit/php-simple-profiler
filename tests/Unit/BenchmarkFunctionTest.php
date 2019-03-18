<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\HRTimer;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Tools\Timer;
use PHPUnit\Framework\TestCase;

class BenchmarkFunctionTest extends TestCase
{
    protected const EXPECTED_RETURN = 3;

    /**
     * @test
     * @throws \Exception
     */
    public function init(): void
    {
        $f = $this->newBenchmarkFunction();
        $expectedReturn = self::EXPECTED_RETURN;
        $b = new BenchmarkFunctionFormatter();
        $this->assertInstanceOf(BenchmarkFunctionFormatter::class, $b);
        $this->assertEquals(PHP_EOL, $b->process($f));
        $f->setBenchmarkRelative(new BenchmarkRelative(1, 0, 0.00001));
        $f->execute();
        $this->assertEquals($expectedReturn, $f->getReturn());
        $this->assertEquals(null, $f->getException());
        $this->assertEquals(
            "1.  10.0μs (  0.00%) hrTestCase(integer, integer) Comment \n integer(3) \n\n",
            $b->process($f)
        );
    }

    /**
     * @return BenchmarkFunction
     * @throws \Exception
     */
    protected function newBenchmarkFunction(): BenchmarkFunction
    {
        $closure = function ($a, $b) {
            return $a + $b;
        };
        $name = 'testCase';
        $comment = 'Comment';
        $humanReadableName = 'hrTestCase';
        $index = 1;
        $args = [1, 2];
        $f =
            new BenchmarkFunction(
                $closure,
                $name,
                $index,
                $args,
                $comment,
                $humanReadableName
            );
        return $f;
    }

    /**
     * @test
     * @throws \Exception
     */
    public function forceNormalTimer(): void
    {
        $value = BenchmarkFunction::isForceRegularTimer();

        BenchmarkFunction::setForceRegularTimer(false);
        $timer = $this->newBenchmarkFunction()->getTimer();
        if (PHP_VERSION_ID < 70300) {
            $this->assertInstanceOf(Timer::class, $timer);
        } else {
            $this->assertInstanceOf(HRTimer::class, $timer);
        }

        BenchmarkFunction::setForceRegularTimer(true);
        $timer = $this->newBenchmarkFunction()->getTimer();
        $this->assertInstanceOf(Timer::class, $timer);

        BenchmarkFunction::setForceRegularTimer($value);
    }
}
