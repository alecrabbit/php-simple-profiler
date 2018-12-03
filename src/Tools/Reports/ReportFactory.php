<?php
/**
 * User: alec
 * Date: 01.12.18
 * Time: 17:14
 */

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Timer;

class ReportFactory
{

    public static function createReport(ReportableInterface $reportable): ReportInterface
    {
        if ($reportable instanceof Timer) {
            return
                new TimerReport($reportable);
        }
        if ($reportable instanceof Counter) {
            return
                new CounterReport($reportable);
        }
        if ($reportable instanceof Profiler) {
            return
                new ProfilerReport($reportable);
        }
        if ($reportable instanceof Benchmark) {
            return
                new BenchmarkReport($reportable);
        }
        throw new \RuntimeException('Attempt to create unimplemented report: ' . typeOf($reportable));
    }
}
