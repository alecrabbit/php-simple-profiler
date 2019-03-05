<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\OldReportInterface;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\Contracts\BenchmarkReportFormatterInterface;
use AlecRabbit\Tools\Reports\Formatters\OldBenchmarkReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\Contracts\BenchmarkFunctionFormatterInterface;
use AlecRabbit\Tools\Reports\Formatters\Contracts\OldFormatter;
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

    /** @var OldBenchmarkReportFormatter */
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
     * @return OldReportInterface
     */
    public static function makeReport(ReportableInterface $reportable): OldReportInterface
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
                new OldBenchmarkReport($reportable);
        }
        throw new \RuntimeException('Attempt to create unimplemented report for: ' . typeOf($reportable));
    }

    /**
     * @param OldReportInterface $report
     * @return OldFormatter
     */
    public static function makeFormatter(OldReportInterface $report): OldFormatter
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
        if ($report instanceof OldBenchmarkReport) {
            return
                self::getOldBenchmarkReportFormatter($report);
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
     * @param OldBenchmarkReport $report
     * @return OldBenchmarkReportFormatter
     */
    protected static function getOldBenchmarkReportFormatter(OldBenchmarkReport $report): OldBenchmarkReportFormatter
    {
//        if (null === static::$benchmarkReportFormatter) {
//            static::$benchmarkReportFormatter = new BenchmarkReportFormatter($report);
//        }
        return
            new OldBenchmarkReportFormatter($report);
    }

    /**
     * @return BenchmarkReportFormatterInterface
     */
    public static function getBenchmarkReportFormatter(): BenchmarkReportFormatterInterface
    {
//        if (null === static::$benchmarkReportFormatter) {
//            static::$benchmarkReportFormatter = new BenchmarkReportFormatter($report);
//        }
        return
            new BenchmarkReportFormatter();
    }

    /**
     * @return BenchmarkFunctionFormatterInterface
     */
    public static function getBenchmarkFunctionFormatter(): BenchmarkFunctionFormatterInterface
    {
        if (null === static::$benchmarkFunctionFormatter) {
            static::$benchmarkFunctionFormatter = new BenchmarkFunctionFormatter();
        }
        return
            static::$benchmarkFunctionFormatter->resetEqualReturns();
    }

    /**
     * @param BenchmarkFunctionFormatterInterface $benchmarkFunctionFormatter
     */
    public static function setBenchmarkFunctionFormatter(
        BenchmarkFunctionFormatterInterface $benchmarkFunctionFormatter
    ): void {
        self::$benchmarkFunctionFormatter = $benchmarkFunctionFormatter;
    }


}
