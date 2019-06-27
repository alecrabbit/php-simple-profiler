<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Aux\WrongFormattable;
use AlecRabbit\Tools\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
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
        $unknownFunction = new WrongFormattable();
        $str = $formatter->format($unknownFunction);
        $this->assertEquals(
            '[AlecRabbit\Tools\Formatters\BenchmarkFunctionFormatter] ERROR: ' .
            'AlecRabbit\Tools\Internal\BenchmarkFunction expected, AlecRabbit\Aux\WrongFormattable given.',
            $str
        );
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
        $this->assertEquals('Array &0 ()', $b->returnToString([]));
        $this->assertInstanceOf(BenchmarkFunctionFormatter::class, $b);
        $this->assertEquals(PHP_EOL, $b->format($f));
        $f->setBenchmarkRelative(new BenchmarkRelative(1, 0, 0.00001));
        $f->execute();
        $this->assertEquals(
            "1.  10.0μs (    0.00%) hrTestCase(integer, integer) Comment \n integer(3) \n\n",
            $b->format($f)
        );
    }
}
