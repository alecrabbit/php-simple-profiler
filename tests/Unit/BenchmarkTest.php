<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkOptions;
use PHPUnit\Framework\TestCase;
use function AlecRabbit\Helpers\callMethod;

class BenchmarkTest extends TestCase
{
    /** @test */
    public function instance(): void
    {
        $b = new Benchmark();
        $this->assertInstanceOf(Benchmark::class, $b);
    }

    /**
     * @test
     * @dataProvider getRevsDataProvider
     * @param int $expected
     * @param int $n
     * @param int|null $shift
     */
    public function getRevs(int $expected, int $n, ?int $shift): void
    {
        $options = new BenchmarkOptions();
        $b = new Benchmark($options);
        $this->assertEquals($expected, callMethod($b, 'getRevs', $n, $shift));
    }

    public function getRevsDataProvider(): array
    {
        return [
            [10, 0, null],
            [10, 1, null],
            [100, 2, null],
            [1000, 3, null],
            [10000, 4, null],
            [100000, 5, null],
            [100000, 6, null],
            [100000, 7, null],
            [15, 0, 5],
            [15, 1, 5],
            [105, 2, 5],
            [1005, 3, 5],
            [10005, 4, 5],
            [100005, 5, 5],
            [100005, 6, 5],
            [100005, 7, 5],
        ];
    }
}
