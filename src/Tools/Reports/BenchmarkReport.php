<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\Contracts\BenchmarkReportInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Core\Report;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\Traits\BenchmarkFields;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class BenchmarkReport extends Report implements BenchmarkReportInterface
{
    use BenchmarkFields;

    protected static function getFormatter(): FormatterInterface
    {
        return
            Factory::getBenchmarkReportFormatter();
    }

    /**
     * @param ReportableInterface $benchmark
     * @return Contracts\ReportInterface
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function buildOn(ReportableInterface $benchmark): ReportInterface
    {
        if ($benchmark instanceof Benchmark) {
            $this->added = $benchmark->getAdded();
            $this->benchmarked = $benchmark->getBenchmarked();
            $this->memoryUsageReport = $benchmark->getMemoryUsageReport();
            $this->doneIterations = $benchmark->getDoneIterations();
            $this->doneIterationsCombined = $benchmark->getDoneIterationsCombined();
            $this->functions = $this->updateFunctions($benchmark->getFunctions());
            $this->timer = $benchmark->getTimer();
        } else {
            $this->wrongReportable(Benchmark::class, $benchmark);
        }
        return $this;
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

    /** {@inheritdoc} */
    public function showReturns(): BenchmarkReportInterface
    {
        foreach ($this->functions as $function) {
            $function->setShowReturns(true);
        }
        return $this;
    }
}
