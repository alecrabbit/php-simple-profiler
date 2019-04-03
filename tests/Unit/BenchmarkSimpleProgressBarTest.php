<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkSimpleProgressBar;
use PHPUnit\Framework\TestCase;

class BenchmarkSimpleProgressBarTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function init(): void
    {
        $b = new BenchmarkSimpleProgressBar(200);
        $this->assertInstanceOf(BenchmarkSimpleProgressBar::class, $b);
        $b->addFunction(
            function () {
            }
        );
        $b->run();
//        $this->assertEquals(Benchmark::DEFAULT_STEPS, $b->getProgressBarWidth());
    }
}
