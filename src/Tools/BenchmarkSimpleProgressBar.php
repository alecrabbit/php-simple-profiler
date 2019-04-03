<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

class BenchmarkSimpleProgressBar extends Benchmark
{
    /** @var int */
    private $progressBarWidth;

    public function __construct(
        int $iterations = 1000
    ) {
        parent::__construct($iterations);
        $width = $this->advanceSteps = $this->terminalWidth();
        $progressStart =
            static function () use ($width): void {
                echo ' [' . str_repeat('░', $width) .']';
                echo "\e[" . ($width + 1) . 'D';
            };

        $progressAdvance =
            static function (): void {
                echo '█';
            };

        $progressFinish =
            static function () use ($width): void {
                echo "\e[" . ($width + 1). 'D';
                echo "\e[K";
            };

        $this->showProgressBy($progressStart, $progressAdvance, $progressFinish);
    }

    /**
     * @return int
     */
    public function getProgressBarWidth(): int
    {
        return $this->progressBarWidth;
    }
}
