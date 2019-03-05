<?php

declare(strict_types=1);

namespace Tests\Unit;

use AlecRabbit\Tools\OldBenchmark;
use AlecRabbit\Tools\OldBenchmarkSimplePB;
use PHPUnit\Framework\TestCase;

class BenchmarkSimplePBTest extends TestCase
{
    /** @test */
    public function init(): void
    {
        $b = new OldBenchmarkSimplePB();
        $this->assertInstanceOf(OldBenchmarkSimplePB::class, $b);
        $b->addFunction(function () {
        });
        $b->run();
        $this->assertEquals(OldBenchmark::DEFAULT_STEPS, $b->getProgressBarWidth());
    }
}
