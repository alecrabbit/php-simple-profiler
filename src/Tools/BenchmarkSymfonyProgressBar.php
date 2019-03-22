<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Terminal;
use function AlecRabbit\Helpers\bounds;

class BenchmarkSymfonyProgressBar extends Benchmark
{
    public const DEFAULT_PROGRESSBAR_FORMAT = ' %percent:3s%% [%bar%] %elapsed:6s%/%estimated:-6s%';
    public const MIN = 60;
    public const MAX = 80;

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


        $this->progressBar = new ProgressBar($this->output, $this->advanceSteps);
        $this->progressBarWidth = $this->refineProgressBarWidth($progressBarWidth);
        $this->progressBar->setBarWidth($this->progressBarWidth);
        $this->progressBar->setFormat(static::DEFAULT_PROGRESSBAR_FORMAT);

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

        $this->showProgressBy($progressStart, $progressAdvance, $progressFinish);
    }

    /**
     * @param null|int $progressBarWidth
     * @return int
     */
    protected function refineProgressBarWidth(?int $progressBarWidth): int
    {
        return
            (int)bounds(
                $progressBarWidth ?? (int)((new Terminal())->getWidth() * 0.8),
                static::MIN,
                static::MAX
            );
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

    protected function showComment(string $comment = ''): void
    {
//        $outputStyle = new OutputFormatterStyle('red', 'yellow', ['bold', 'blink']);
//        $this->output->getFormatter()->setStyle('fire', $outputStyle);
//
        $this->output->writeln('<comment>' . $comment . '</>');
    }
}
