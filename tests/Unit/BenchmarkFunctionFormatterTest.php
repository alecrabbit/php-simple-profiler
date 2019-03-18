<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Formattable;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;
use PHPUnit\Framework\TestCase;

class BenchmarkFunctionFormatterTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function wrongFunction(): void
    {
        $formatter = new BenchmarkFunctionFormatter();
        $unknownFunction =
            new class extends Formattable
            {
            };
        $this->expectException(\RuntimeException::class);
        $formatter->process($unknownFunction);
    }


    /** @test */
    public function init(): void
    {
        $closure = function ($a, $b) {
            return $a + $b;
        };
        $name = 'testCase';
        $comment = 'Comment';
        $humanReadableName = 'hrTestCase';
        $index = 1;
        $args = [1, 2];
        $expectedReturn = 3;
        $f =
            new BenchmarkFunction(
                $closure,
                $name,
                $index,
                $args,
                $comment,
                $humanReadableName
            );
        $b = new BenchmarkFunctionFormatter();
        $this->assertInstanceOf(BenchmarkFunctionFormatter::class, $b);
        $this->assertEquals(PHP_EOL, $b->process($f));
        $f->setBenchmarkRelative(new BenchmarkRelative(1, 0, 0.00001));
        $f->execute();
        $this->assertEquals(
            "1.  10.0μs (  0.00%) hrTestCase(integer, integer) Comment \n integer(3) \n\n",
            $b->process($f)
        );
    }

    /** @test */
    public function returnToString(): void
    {
        $this->assertEquals(
            'Array &0 ()',
            BenchmarkFunctionFormatter::returnToString([])
        );
    }
}