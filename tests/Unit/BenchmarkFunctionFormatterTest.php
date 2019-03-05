<?php declare(strict_types=1);

namespace Tests\Unit;

use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;
use PHPUnit\Framework\TestCase;

class BenchmarkFunctionFormatterTest extends TestCase
{
    /** @test */
    public function init(): void
    {
        $f =
            new BenchmarkFunction(
                function ($a, $b) {
                    return $this;
                },
                'testCase',
                1,
                [1, 2],
                'Comment',
                'hrTestCase'
            );
        $b = new BenchmarkFunctionFormatter($f);
        $this->assertInstanceOf(BenchmarkFunctionFormatter::class, $b);
    }

    /** @test */
    public function returnToString(): void
    {
        $this->assertEquals(
            'Tests\Unit\BenchmarkFunctionFormatterTest([PHPUnit\Framework\Error\Warning]' .
            ' var_export does not handle circular references)',
            BenchmarkFunctionFormatter::returnToString($this)
        );
    }
}
