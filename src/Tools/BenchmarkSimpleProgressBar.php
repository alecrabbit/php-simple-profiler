<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

class BenchmarkSimpleProgressBar extends Benchmark
{
    public function __construct(
        int $iterations = 1000,
        bool $quiet = false
    ) {
        parent::__construct($iterations);
        $width = $this->advanceSteps = $this->terminalWidth();
        // @codeCoverageIgnoreStart
        if (!$quiet) {
            $progressStart =
                static function () use ($width): void {
                    echo ' [' . str_repeat('░', $width) . ']';
                    echo "\e[" . ($width + 1) . 'D';
                };
            $progressAdvance =
                static function (): void {
                    echo '█';
                };

            $progressFinish =
                static function () use ($width): void {
                    echo "\e[" . ($width + 1) . 'D';
                    echo "\e[K";
                };

            $this->showProgressBy($progressStart, $progressAdvance, $progressFinish);
        }
        // @codeCoverageIgnoreEnd
    }
}
