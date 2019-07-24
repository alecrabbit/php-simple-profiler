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

    /** @test */
    public function getRevsDirect(): void
    {
        $options = new BenchmarkOptions();
        $options->setMethod(BenchmarkOptions::DIRECT_MEASUREMENTS);
        $b = new Benchmark($options);
        $this->assertEquals(1, callMethod($b, 'getRevs', 0));
        $this->assertEquals(2, callMethod($b, 'getRevs', 1));
        $this->assertEquals(3, callMethod($b, 'getRevs', 2));
        $this->assertEquals(10, callMethod($b, 'getRevs', 3));
        $this->assertEquals(65, callMethod($b, 'getRevs', 4));
        $this->assertEquals(626, callMethod($b, 'getRevs', 5));
        $this->assertEquals(7777, callMethod($b, 'getRevs', 6));
        $this->assertEquals(117650, callMethod($b, 'getRevs', 7));
        $this->assertEquals(117650, callMethod($b, 'getRevs', 8));
    }

    /** @test */
    public function getRevsIndirect(): void
    {
        $options = new BenchmarkOptions();
        $options->setMethod(BenchmarkOptions::INDIRECT_MEASUREMENTS);
        $b = new Benchmark($options);
        $this->assertEquals(1, callMethod($b, 'getRevs', 0));
        $this->assertEquals(10, callMethod($b, 'getRevs', 1));
        $this->assertEquals(100, callMethod($b, 'getRevs', 2));
        $this->assertEquals(1000, callMethod($b, 'getRevs', 3));
        $this->assertEquals(10000, callMethod($b, 'getRevs', 4));
        $this->assertEquals(100000, callMethod($b, 'getRevs', 5));
        $this->assertEquals(100000, callMethod($b, 'getRevs', 6));
        $this->assertEquals(100000, callMethod($b, 'getRevs', 7));
        $this->assertEquals(100000, callMethod($b, 'getRevs', 8));
    }
}
