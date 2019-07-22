<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Benchmark;
use PHPUnit\Framework\TestCase;

class BenchmarkTest extends TestCase
{
    /** @test */
    public function instance(): void
    {
        $b = new Benchmark();
        $this->assertInstanceOf(Benchmark::class, $b);
    }
}
