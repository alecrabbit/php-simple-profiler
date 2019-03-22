<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\BenchmarkSymfonyProgressBar;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class BenchmarkSymfonyProgressBarTest extends TestCase
{
    /** @test */
    public function init(): void
    {
        $b = new BenchmarkSymfonyProgressBar(
            100,
            null,
            null,
            new ConsoleOutput(OutputInterface::VERBOSITY_QUIET)
        );
        $this->assertInstanceOf(BenchmarkSymfonyProgressBar::class, $b);
        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(ConsoleOutput::class, $b->getOutput());
        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(ProgressBar::class, $b->getProgressBar());
        $b->addFunction(function () {
        });
        $b->run();
        $this->assertTrue(
            $this->valueInRange(
                $b->getProgressBarWidth(),
                BenchmarkSymfonyProgressBar::PROGRESS_BAR_MIN_WIDTH,
                BenchmarkSymfonyProgressBar::PROGRESS_BAR_MAX_WIDTH
            )
        );
    }

    protected function valueInRange(int $value, int $min, int $max): bool
    {
        return
            ($value >= $min && $value <= $max);
    }
}
