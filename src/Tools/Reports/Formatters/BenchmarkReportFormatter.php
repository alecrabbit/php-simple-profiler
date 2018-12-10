<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:22
 */
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Reports\BenchmarkReport;
use function AlecRabbit\brackets;
use function AlecRabbit\typeOf;

class BenchmarkReportFormatter extends Formatter
{
    /** @var BenchmarkReport */
    protected $report;

    public function getString(): string
    {
        $profilerReport = (string)$this->report->getProfiler()->getReport();
        $r = 'Benchmark:' . PHP_EOL;
        foreach ($this->report->getRelatives() as $indexName => $result) {
            $function = $this->report->getFunctionObject($indexName);
            $arguments = $function->getArgs();
            $types = [];
            if (!empty($arguments)) {
                foreach ($arguments as $argument) {
                    $types[] = typeOf($argument);
                }
            }
            $r .= sprintf(
                '+%s %s(%s) %s %s',
                $result,
                $function->getIndexedName(),
                implode(', ', $types),
                $function->getComment(),
                PHP_EOL
            );
            if ($this->report->isWithResults()) {
                $r .= var_export($function->getResult(), true) . PHP_EOL;
            }
        }
        if (!empty($exceptionMessages = $this->report->getExceptionMessages())) {
            $r .= 'Exceptions:' . PHP_EOL;
            foreach ($exceptionMessages as $name => $exceptionMessage) {
                $r .= brackets($name) . ': ' . $exceptionMessage . PHP_EOL;
            }
        }
        return
            $r . PHP_EOL . $profilerReport;
    }
}