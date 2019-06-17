<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Reports\Core\AbstractReport;
use AlecRabbit\Reports\Core\AbstractReportable;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\Contracts\BenchmarkReportInterface;
use AlecRabbit\Tools\Traits\BenchmarkFields;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class BenchmarkReport extends AbstractReport implements BenchmarkReportInterface
{
    use BenchmarkFields;

    /** {@inheritDoc}
     * @throws \Exception
     */
    protected function extractDataFrom(AbstractReportable $reportable = null): void
    {
        if ($reportable instanceof Benchmark) {
            $this->added = $reportable->getAdded();
            $this->benchmarked = $reportable->getBenchmarked();
            $this->memoryUsageReport = $reportable->getMemoryUsageReport();
            $this->doneIterations = $reportable->getDoneIterations();
            $this->doneIterationsCombined = $reportable->getDoneIterationsCombined();
            $this->functions = $this->updateFunctions($reportable->getFunctions());
            $this->timer = $reportable->getTimer();
            $this->showReturns = $reportable->isShowReturns();
        }
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
        $this->showReturns = true;
        foreach ($this->functions as $function) {
            $function->setShowReturns($this->showReturns);
        }
        return $this;
    }
}
