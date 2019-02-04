<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Pretty;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use function AlecRabbit\brackets;
use function AlecRabbit\typeOf;

class BenchmarkReportFormatter extends Formatter
{
    /** @var BenchmarkReport */
    protected $report;

    /**
     * {@inheritdoc}
     */
    public function getString(): string
    {
        $rank = 0;
        $profilerReport = (string)$this->report->getProfiler()->getReport();
        $r = 'Benchmark:' . PHP_EOL;
        /** @var BenchmarkRelative $result */
        foreach ($this->report->getRelatives() as $indexName => $result) {
            $relative = $result->getRelative();
            $average = $result->getAverage();
            $function = $this->report->getFunctionObject($indexName);
            $function->setRank(++$rank);
            $arguments = $function->getArgs();
            $types = [];
            if (!empty($arguments)) {
                foreach ($arguments as $argument) {
                    $types[] = typeOf($argument);
                }
            }
            $r .= sprintf(
                '%s. %s (%s) %s(%s) %s %s',
                (string)$rank,
                $this->average($average),
                $this->relativePercent($relative),
                $function->getHumanReadableName(),
                implode(', ', $types),
                $function->getComment(),
                PHP_EOL
            );
            if ($this->report->isWithResults()) {
                $result = $function->getResult();
                var_dump($result);
                dump($result);
                $r .= self::RESULT . ': ' . $this->typeOf($result) . ' "'
                    . var_export($function->getResult(), true) . '" ' . PHP_EOL;
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

    /**
     * @param float $average
     * @return string
     */
    protected function average(float $average): string
    {
        return str_pad(
            Pretty::time($average),
            8,
            ' ',
            STR_PAD_LEFT
        );
    }

    /**
     * @param float $relative
     * @return string
     */
    protected function relativePercent(float $relative): string
    {
        return str_pad(
            Pretty::percent($relative),
            7,
            ' ',
            STR_PAD_LEFT
        );
    }

    /**
     * @param mixed $result
     * @return string
     */
    protected function typeOf($result): string
    {
        return str_replace('double', 'float', typeOf($result));
    }
}
