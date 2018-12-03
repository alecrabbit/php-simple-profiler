<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 20:56
 */

namespace AlecRabbit\Tools\Reports;

use function AlecRabbit\brackets;
use const AlecRabbit\Constants\Accessories\DEFAULT_NAME;
use const AlecRabbit\Constants\BRACKETS_PARENTHESES;
use function AlecRabbit\format_time;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Internal\BenchmarkedFunction;
use AlecRabbit\Tools\Reports\Base\Report;
use AlecRabbit\Tools\Timer;
use AlecRabbit\Tools\Traits\BenchmarkFields;
use function AlecRabbit\typeOf;

class BenchmarkReport extends Report
{
    use BenchmarkFields;

    /**
     * BenchmarkReport constructor.
     * @param Benchmark $reportable
     */
    public function __construct(Benchmark $reportable)
    {
        $this->profiler = $reportable->getProfiler();
        $this->functions = $reportable->getFunctions();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $r = (string)$this->getProfiler()->report();
        $r .= 'Benchmark:' . PHP_EOL;
        foreach ($this->computeRelatives() as $indexName => $result) {
            $function = $this->getFunctionObject($indexName);
            $arguments = $function->getArgs();
            $types = [];
            if (!empty($arguments)) {
                foreach ($arguments as $argument) {
                    $types[] = typeOf($argument);
                }
            }
            $r .= sprintf(
                '+%s [%s] %s(%s) %s' . PHP_EOL,
                $result,
                $function->getIndex(),
                $function->getName(),
                implode(', ', $types),
                $function->getComment()
            );
        }
        return
            $r;
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
     * @param string $name
     * @return BenchmarkedFunction
     */
    private function getFunctionObject(string $name): BenchmarkedFunction
    {
        return $this->functions[$name];
    }
}
