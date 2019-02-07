<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\Base\Report;
use AlecRabbit\Tools\Traits\BenchmarkFields;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class BenchmarkReport extends Report
{
    use BenchmarkFields;

    /** @var array */
    protected $relatives;

    /**
     * BenchmarkReport constructor.
     * @param Benchmark $benchmark
     */
    public function __construct(Benchmark $benchmark)
    {
        $this->profiler = $benchmark->getProfiler();
        $this->doneIterations = $benchmark->getDoneIterations();
        $this->functions = $this->updateFunctions($benchmark->getFunctions());

        parent::__construct();
    }

    /**
     * @param array $functions
     * @return array
     */
    private function updateFunctions(array $functions): array
    {
        $averages = $this->computeAverages($functions);
        $relatives = $this->computeRelatives($averages);
        $updatedFunctions = [];
        if (!empty($relatives)) {
            $rank = 0;
            /** @var BenchmarkFunction $function */
            foreach ($functions as $name => $function) {
                $relative = $relatives[$name] ?? null;
                $average = $averages[$name] ?? null;
                if (null !== $relative && null !== $average) {
                    $function->setBenchmarkRelative(
                        new BenchmarkRelative(++$rank, (float)$relative - 1, (float)$average)
                    );
                }
                $updatedFunctions[$name] = $function;
            }
        }
        return $updatedFunctions;
    }

    /**
     * @param array $functions
     * @return array
     */
    private function computeAverages(array $functions): array
    {
        $averages = [];
        /** @var BenchmarkFunction $f */
        foreach ($functions as $f) {
            $timer = $f->getTimer();
            if ((DEFAULT_NAME !== $name = $timer->getName())
                && 0.0 !== $avg = $timer->getAverageValue()) {
                $averages[$name] = $avg;
            }
        }
        return $averages;
    }

    private function computeRelatives(array $averages): array
    {
        $rel = [];
        if (!empty($averages)) {
            $min = min($averages);

            foreach ($averages as $name => $average) {
                $rel[$name] = $average / $min;
            }
            asort($rel);
        }
        return $rel;
    }

    /**
     * @return array[BenchmarkFunction]
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }
//    /**
//     * @param array $functions
//     * @return array
//     */
//    private function updateFunctions(array $functions): array
//    {
//        $averages = $this->computeAverages($functions);
//        $relatives = [];
//        if (!empty($averages)) {
//            $min = min($averages);
//
//            foreach ($averages as $name => $average) {
//                $rel[$name] = $average / $min;
//            }
//            asort($rel);
//            $rank = 0;
//            /** @var BenchmarkFunction $f */
//            foreach ($rel as $name => $r) {
//                $f = $functions[$name];
//                $relatives[$name] =
//                    $f->setBenchmarkRelative(
//                        new BenchmarkRelative(++$rank, (float)$r - 1, $averages[$name])
//                    );
//            }
//        }
//        return $functions;
//    }
}
