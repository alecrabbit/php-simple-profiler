<?php

declare(strict_types=1);

namespace Tests\Unit;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkSimplePB;
use PHPUnit\Framework\TestCase;

class BenchmarkSimplePBTest extends TestCase
{
    /** @test */
    public function init(): void
    {
        $b = new BenchmarkSimplePB();
        $this->assertInstanceOf(BenchmarkSimplePB::class, $b);
        $b->addFunction(function () {
        });
        $b->run();
        $this->assertEquals(Benchmark::DEFAULT_STEPS, $b->getProgressBarWidth());
    }
}
