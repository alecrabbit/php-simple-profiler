<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 20:56
 */

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\Base\Report;
use AlecRabbit\Tools\Timer;
use AlecRabbit\Tools\Traits\BenchmarkFields;
use function AlecRabbit\brackets;
use function AlecRabbit\format_time;
use const AlecRabbit\Constants\Accessories\DEFAULT_NAME;
use const AlecRabbit\Constants\BRACKETS_PARENTHESES;

class BenchmarkReport extends Report
{
    use BenchmarkFields;

    protected $relatives;

    /**
     * BenchmarkReport constructor.
     * @param Benchmark $report
     */
    public function __construct(Benchmark $report)
    {
        $this->profiler = $report->getProfiler();
        $this->functions = $report->getFunctions();
        $this->totalIterations = $report->getTotalIterations();
        $this->withResults = $report->isWithResults();
        $this->exceptionMessages = $report->getExceptionMessages();
        $this->relatives = $this->computeRelatives();

        parent::__construct($this);
    }

    /**
     * @return array
     */
    private function computeRelatives(): array
    {
        $averages = $this->computeAverages(
            $this->profiler->getTimers()
        );

        $min = min($averages);

        $relatives = [];
        foreach ($averages as $name => $average) {
            $relatives[$name] = $average / $min;
        }
        asort($relatives);

        foreach ($relatives as $name => $relative) {
            $relatives[$name] =
                $this->percent((float)$relative - 1) . ' ' .
                brackets(format_time($averages[$name]), BRACKETS_PARENTHESES);
        }
        return $relatives;
    }

    /**
     * @param array $timers
     * @return array
     */
    private function computeAverages(array $timers): array
    {
        $averages = [];
        /** @var Timer $timer */
        foreach ($timers as $timer) {
            if (DEFAULT_NAME !== $name = $timer->getName()) {
                $averages[$name] = $timer->getAverageValue();
            }
        }
        return $averages;
    }

    /**
     * @param float $relative
     * @return string
     */
    private function percent(float $relative): string
    {
        return
            number_format($relative * 100, 1) . '%';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return
            $this->formatter->getString();
    }

    /**
     * @param string $name
     * @return BenchmarkFunction
     */
    public function getFunctionObject(string $name): BenchmarkFunction
    {
        return $this->functions[$name];
    }

    /**
     * @return array
     */
    public function getRelatives(): array
    {
        return $this->relatives;
    }
}
