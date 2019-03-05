<?php

declare(strict_types=1);

namespace AlecRabbit\Tools;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

// BenchmarkSymfonyProgressBar
class OldBenchmarkSymfonyPB extends OldBenchmark
{
    public const DEFAULT_PROGRESSBAR_FORMAT = '[%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%';
    public const PROGRESS_BAR_WIDTH = 80;

    /** @var ConsoleOutput */
    protected $output;

    /** @var ProgressBar */
    protected $progressBar;

    /** @var int */
    private $progressBarWidth;

    public function __construct(
        int $iterations = 1000,
        ?int $progressBarMax = null,
        ?int $progressBarWidth = null,
        ?ConsoleOutput $output = null
    ) {
        parent::__construct($iterations);
        $this->output = $output ?? new ConsoleOutput();
        $this->advanceSteps = $progressBarMax ?? $this->advanceSteps;
        $this->progressBarWidth = $progressBarWidth ?? self::PROGRESS_BAR_WIDTH;

        $this->progressBar = new ProgressBar($this->output, $this->advanceSteps);
        $this->progressBar->setBarWidth($this->progressBarWidth);
        $progressStart =
            function (): void {
                $this->progressBar->start();
            };

        $progressAdvance =
            function (): void {
                $this->progressBar->advance();
            };

        $progressFinish =
            function (): void {
                $this->progressBar->finish();
                $this->progressBar->clear();
            };

        $this->progressBar->setFormat(static::DEFAULT_PROGRESSBAR_FORMAT);
        $this->showProgressBy($progressStart, $progressAdvance, $progressFinish);
    }

    /**
     * @return ConsoleOutput
     */
    public function getOutput(): ConsoleOutput
    {
        return $this->output;
    }

    /**
     * @return ProgressBar
     */
    public function getProgressBar(): ProgressBar
    {
        return $this->progressBar;
    }

    /**
     * @return int
     */
    public function getProgressBarWidth(): int
    {
        return $this->progressBarWidth;
    }
}
