<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\Contracts\Formatter;
use AlecRabbit\Tools\Reports\Formatters\CounterReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\ProfilerReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\TimerReportFormatter;
use AlecRabbit\Tools\Timer;
use function AlecRabbit\typeOf;

class Factory
{
    /** @var TimerReportFormatter */
    protected static $timerReportFormatter;

    /** @var CounterReportFormatter */
    protected static $counterReportFormatter;

    /** @var ProfilerReportFormatter */
    protected static $profilerReportFormatter;

    /** @var BenchmarkReportFormatter */
    protected static $benchmarkReportFormatter;

    /** @var BenchmarkFunctionFormatter */
    protected static $benchmarkFunctionFormatter;

    /** @codeCoverageIgnore */
    private function __construct()
    {
        // Static class
    }

    /**
     * @param ReportableInterface $reportable
     * @return ReportInterface
     */
    public static function makeReport(ReportableInterface $reportable): ReportInterface
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
        throw new \RuntimeException('Attempt to create unimplemented report for: ' . typeOf($reportable));
    }

    /**
     * @param ReportInterface $report
     * @return Formatter
     */
    public static function makeFormatter(ReportInterface $report): Formatter
    {
        if ($report instanceof TimerReport) {
            return
                self::getTimerReportFormatter($report);
        }
        if ($report instanceof CounterReport) {
            return
                self::getCounterReportFormatter($report);
        }
        if ($report instanceof ProfilerReport) {
            return
                self::getProfilerReportFormatter($report);
        }
        if ($report instanceof BenchmarkReport) {
            return
                self::getBenchmarkReportFormatter($report);
        }
        throw new \RuntimeException('Attempt to create unimplemented formatter for: ' . typeOf($report));
    }

    /**
     * @param TimerReport $report
     * @return TimerReportFormatter
     */
    protected static function getTimerReportFormatter(TimerReport $report): TimerReportFormatter
    {
//        if (null === static::$timerReportFormatter) {
//            static::$timerReportFormatter = new TimerReportFormatter($report);
//        }
        return
            new TimerReportFormatter($report);
    }

    /**
     * @param CounterReport $report
     * @return CounterReportFormatter
     */
    protected static function getCounterReportFormatter(CounterReport $report): CounterReportFormatter
    {
//        if (null === static::$counterReportFormatter) {
//            static::$counterReportFormatter = new CounterReportFormatter($report);
//        }
        return
            new CounterReportFormatter($report);
    }

    /**
     * @param ProfilerReport $report
     * @return ProfilerReportFormatter
     */
    protected static function getProfilerReportFormatter(ProfilerReport $report): ProfilerReportFormatter
    {
//        if (null === static::$profilerReportFormatter) {
//            static::$profilerReportFormatter = new ProfilerReportFormatter($report);
//        }
        return
            new ProfilerReportFormatter($report);
    }

    /**
     * @param BenchmarkReport $report
     * @return BenchmarkReportFormatter
     */
    protected static function getBenchmarkReportFormatter(BenchmarkReport $report): BenchmarkReportFormatter
    {
//        if (null === static::$benchmarkReportFormatter) {
//            static::$benchmarkReportFormatter = new BenchmarkReportFormatter($report);
//        }
        return
            new BenchmarkReportFormatter($report);
    }

    /**
     * @return BenchmarkFunctionFormatter
     */
    public static function getBenchmarkFunctionFormatter(): BenchmarkFunctionFormatter
    {
        if (null === static::$benchmarkFunctionFormatter) {
            static::$benchmarkFunctionFormatter = new BenchmarkFunctionFormatter();
        }
        return
            static::$benchmarkFunctionFormatter->resetEqualReturns();
    }
}
