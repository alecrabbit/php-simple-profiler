<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

class BenchmarkSimpleProgressBar extends Benchmark
{
    /** @var int */
    private $progressBarWidth;

    public function __construct(
        int $iterations = 1000,
        ?int $progressBarWidth = null
    ) {
        parent::__construct($iterations);
        $this->progressBarWidth = $this->advanceSteps = $progressBarWidth ?? $this->advanceSteps;

        $progressAdvance =
            function (): void {
                echo '*';
            };

        $progressFinish =
            function (): void {
                echo "\e[" . $this->progressBarWidth . 'D';
                echo "\e[K";
            };

        $this->showProgressBy(null, $progressAdvance, $progressFinish);
    }

    /**
     * @return int
     */
    public function getProgressBarWidth(): int
    {
        return $this->progressBarWidth;
    }
}
