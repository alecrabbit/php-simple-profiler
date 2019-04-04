<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Accessories\Circular;
use AlecRabbit\ConsoleColour\Terminal;
use AlecRabbit\Tools\Contracts\Strings;

class BenchmarkSnakeProgressIndicator extends Benchmark
{
    public function __construct(
        int $iterations = 1000,
        bool $quiet = false
    ) {
        parent::__construct($iterations);
        $this->advanceSteps = $this->terminalWidth();
        if (!$quiet) {
            $symbols = new Circular(['⠋', '⠙', '⠹', '⠸', '⠼', '⠴', '⠦', '⠧', '⠇', '⠏']);
            $styles = $this->createStyles();
            $str = ' Benchmarking...';
            $len = strlen($str) + 1;
            $resetStr = Strings::ESC . "[{$len}D";
            if (null !== $styles) {
                $style = static function () use ($symbols, $styles): string {
                    return Strings::ESC . "[{$styles->value()}m" . $symbols->value() . Strings::ESC . '[0m';
                };
            } else {
                $style = static function () use ($symbols, $str): string {
                    return $symbols->value() . $str;
                };
            }
            $f = static function () use ($str, $style): void {
                echo $style() . $str;
            };
            $progressStart = $progressAdvance =
                static function () use ($f, $resetStr): void {
                    echo $resetStr;
                    $f();
                };

            $progressFinish =
                static function () use ($resetStr): void {
                    echo $resetStr;
                    echo Strings::ESC . '[K';
                };

            $this->showProgressBy($progressStart, $progressAdvance, $progressFinish);
        }
    }

    protected function createStyles(): ?Circular
    {
        if ((new Terminal())->supports256Color()) {
            return new Circular([
                '38;5;197',
                '38;5;198',
                '38;5;199',
                '38;5;200',
                '38;5;201',
                '38;5;202',
                '38;5;203',
                '38;5;204',
                '38;5;205',
            ]);
        }
        if ((new Terminal())->supportsColor()) {
            return new Circular([
                '96',
            ]);
        }
        return null;
    }
}
