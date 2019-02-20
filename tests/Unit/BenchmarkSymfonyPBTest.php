<?php

declare(strict_types=1);

namespace Tests\Unit;

use AlecRabbit\Tools\BenchmarkSymfonyPB;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class BenchmarkSymfonyPBTest extends TestCase
{
    /** @test */
    public function init(): void
    {
        $b = new BenchmarkSymfonyPB();
        $this->assertInstanceOf(BenchmarkSymfonyPB::class, $b);
        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(ConsoleOutput::class, $b->getOutput());
        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(ProgressBar::class, $b->getProgressBar());
        $b->addFunction(function () {
        });
        $b->run();
        $this->assertEquals(BenchmarkSymfonyPB::PROGRESS_BAR_WIDTH, $b->getProgressBarWidth());
    }
}
