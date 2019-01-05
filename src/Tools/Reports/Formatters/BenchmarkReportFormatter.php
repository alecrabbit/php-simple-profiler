<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:22
 */
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use function AlecRabbit\brackets;
use function AlecRabbit\format_time_auto;
use function AlecRabbit\typeOf;

class BenchmarkReportFormatter extends Formatter
{
    /** @var BenchmarkReport */
    protected $report;

    public function setStyles(): void
    {
    }

    /**
     * {@inheritdoc}
     * @throws \Throwable
     */
    public function getString(): string
    {
        $profilerReport = (string)$this->report->getProfiler()->getReport();
        $r = 'Benchmark:' . PHP_EOL;
        /** @var BenchmarkRelative $result */
        foreach ($this->report->getRelatives() as $indexName => $result) {
            $relative = $result->getRelative();
            $average = $result->getAverage();
            $function = $this->report->getFunctionObject($indexName);
            $arguments = $function->getArgs();
            $types = [];
            if (!empty($arguments)) {
                foreach ($arguments as $argument) {
                    $types[] = typeOf($argument);
                }
            }
            $r .= sprintf(
                '%s (+%s) %s(%s) %s %s',
                $this->themed->yellow(format_time_auto($average)),
                $this->col($relative),
                $function->getIndexedName(),
                implode(', ', $types),
                $this->themed->comment($function->getComment()),
                PHP_EOL
            );
            if ($this->report->isWithResults()) {
                $result = $function->getResult();
                $r .= $this->themed->dark('return: ' . str_replace('double', 'float', typeOf($result)) . ' "'
                        . var_export($function->getResult(), true) . '" ') . PHP_EOL;
            }
        }
        if (!empty($exceptionMessages = $this->report->getExceptionMessages())) {
            $r .= 'Exceptions:' . PHP_EOL;
            foreach ($exceptionMessages as $name => $exceptionMessage) {
                $r .= brackets($name) . ': ' . $this->themed->red($exceptionMessage) . PHP_EOL;
            }
        }
        return
            $r . PHP_EOL . $profilerReport;
    }

    /**
     * @param float $relative
     * @return string
     * @throws \Throwable
     */
    private function col($relative): string
    {
        if ($relative > 1) {
            return $this->themed->red($this->percent($relative));
        }
        if ($relative >= 0.03) {
            return $this->themed->yellow($this->percent($relative));
        }
        return $this->themed->green($this->percent($relative));
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
}
