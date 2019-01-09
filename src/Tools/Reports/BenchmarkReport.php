<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 20:56
 */

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Exception\InvalidStyleException;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\Base\Report;
use AlecRabbit\Tools\Timer;
use AlecRabbit\Tools\Traits\BenchmarkFields;
use const AlecRabbit\Constants\Traits\DEFAULT_NAME;

class BenchmarkReport extends Report
{
    use BenchmarkFields;

    /** @var array */
    protected $relatives;

    /**
     * BenchmarkReport constructor.
     * @param Benchmark $benchmark
     * @throws InvalidStyleException
     */
    public function __construct(Benchmark $benchmark)
    {
        $this->profiler = $benchmark->getProfiler();
        $this->functions = $benchmark->getFunctions();
        $this->totalIterations = $benchmark->getTotalIterations();
        $this->withResults = $benchmark->isWithResults();
        $this->exceptionMessages = $benchmark->getExceptionMessages();
        $this->exceptions = $benchmark->getExceptions();
        $this->relatives = $this->computeRelatives();

        parent::__construct();
    }

    /**
     * @return array
     */
    private function computeRelatives(): array
    {
        $averages = $this->computeAverages($this->profiler->getTimers());
        $relatives = [];
        if (!empty($averages)) {
            $min = min($averages);

            foreach ($averages as $name => $average) {
                $relatives[$name] = $average / $min;
            }
            asort($relatives);

            /** @var  float|int $relative */
            foreach ($relatives as $name => $relative) {
                $relatives[$name] = new BenchmarkRelative((float)$relative - 1, $averages[$name]);
            }
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
                try {
                    $averages[$name] = $timer->getAverageValue();
                } catch (\Throwable $e) {
                    // no further action
                }
            }
        }
        return $averages;
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
    public function getFunctionObjects(): array
    {
        return $this->functions;
    }

    /**
     * @return array
     */
    public function getRelatives(): array
    {
        return $this->relatives;
    }
}
