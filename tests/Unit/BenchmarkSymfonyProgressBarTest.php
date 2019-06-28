<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\BenchmarkSymfonyProgressBar;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class BenchmarkSymfonyProgressBarTest extends TestCase
{
    /** @test */
    public function init(): void
    {
        $b =
            new BenchmarkSymfonyProgressBar(
                100,
                null,
                new ConsoleOutput(OutputInterface::VERBOSITY_QUIET)
            );
        $this->assertInstanceOf(BenchmarkSymfonyProgressBar::class, $b);
        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(ConsoleOutput::class, $b->getOutput());
        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(ProgressBar::class, $b->getProgressBar());
        $b->add(function () {
        });
        $b->run();
        $this->assertInstanceOf(BenchmarkReport::class, $b->report());
    }
}
