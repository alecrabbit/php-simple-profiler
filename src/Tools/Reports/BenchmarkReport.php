<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\OldBenchmark;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\Contracts\BenchmarkReportInterface;
use AlecRabbit\Tools\Reports\Core\Report;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\Traits\BenchmarkFields;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class BenchmarkReport extends Report implements BenchmarkReportInterface
{
    use BenchmarkFields;

    /**
     * BenchmarkReport constructor.
     * @param OldBenchmark $benchmark
     */
    public function __construct(OldBenchmark $benchmark)
    {
        $this->profiler = $benchmark->getProfiler();
        $this->memoryUsageReport = $benchmark->getMemoryUsageReport();
        $this->doneIterations = $benchmark->getDoneIterations();
        $this->doneIterationsCombined = $benchmark->getDoneIterationsCombined();
        $this->functions = $this->updateFunctions($benchmark->getFunctions());
        $this->timer = $benchmark->getTimer();
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
            foreach ($relatives as $name => $relative) {
                $function = $functions[$name] ?? null;
                $average = $averages[$name] ?? null;
                if (null !== $function && null !== $average) {
                    $function->setBenchmarkRelative(
                        new BenchmarkRelative(++$rank, (float)$relative - 1, (float)$average)
                    );
                }
                unset($functions[$name]);
                $updatedFunctions[$name] = $function;
            }
        }
        foreach ($functions as $name => $function) {
            $updatedFunctions[$name] = $function;
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

    protected static function getFormatter(): FormatterInterface
    {
        return
            Factory::getFormatterFor(static::class);
    }

    /**
     * @return BenchmarkFunction[]
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }

    /**
     * {@inheritdoc}
     */
    public function noReturns(): BenchmarkReportInterface
    {
        return $this;
    }
}
