<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Pretty;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Reports\BenchmarkReport;
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
        $profilerReport = (string)$this->report->getProfiler()->getReport();
        $r = 'Benchmark:' . PHP_EOL;
        $withException = '';
        /** @var BenchmarkFunction $function */
        foreach ($this->report->getFunctions() as $name => $function) {
            dump($function);
            $br = $function->getBenchmarkRelative();
            $arguments = $function->getArgs();
            $types = [];
            if (!empty($arguments)) {
                foreach ($arguments as $argument) {
                    $types[] = typeOf($argument);
                }
            }

            if ($br) {
                $relative = $br->getRelative();
                $average = $br->getAverage();
                $rank = $br->getRank();
                $r .=
                    sprintf(
                        '%s. %s (%s) %s(%s) %s %s',
                        (string)$rank,
                        $this->average($average),
                        $this->relativePercent($relative),
                        $function->humanReadableName(),
                        implode(', ', $types),
                        $function->comment(),
                        PHP_EOL
                    );
                $result = $function->getResult();
                $r .=
                    sprintf(
                        '%s(%s) %s',
                        $this->typeOf($result),
                        var_export($result, true),
                        PHP_EOL
                    );
            } else {
                $withException .= sprintf(
                    '%s(%s) %s %s %s',
                    $function->humanReadableName(),
                    implode(', ', $types),
                    $function->comment(),
                    $function->getException()->getMessage(),
                    PHP_EOL
                );
            }
        }
        return
            $r . PHP_EOL .
            (empty($withException) ? '' : 'Exceptions:' . PHP_EOL . $withException) . PHP_EOL .
            $profilerReport;
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
