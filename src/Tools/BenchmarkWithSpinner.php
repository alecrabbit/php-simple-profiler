<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Spinner\Core\Contracts\SpinnerInterface;
use AlecRabbit\Spinner\SnakeSpinner;

class BenchmarkWithSpinner extends Benchmark
{
    public function __construct(
        int $iterations = 1000,
        bool $quiet = false,
        SpinnerInterface $spinner = null
    ) {
        parent::__construct($iterations);
        $this->advanceSteps = $this->terminalWidth();
        // @codeCoverageIgnoreStart
        if (!$quiet) {
            $s = $spinner ?? new SnakeSpinner('Benchmarking');
            $progressStart =
                static function () use ($s): void {
                    $s->begin(0.0);
                };

            $progressAdvance =
                static function (?float $percent = null) use ($s): void {
                    $s->spin($percent);
                };

            $progressFinish =
                static function () use ($s): void {
                    $s->end();
                };

            $this->showProgressBy($progressStart, $progressAdvance, $progressFinish);
        }
        // @codeCoverageIgnoreEnd
    }
}
