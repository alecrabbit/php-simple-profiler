<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\CounterReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\ProfilerReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\TimerReportFormatter;

class Factory
{
    /** @var null|TimerReportFormatter */
    protected static $timerReportFormatter;

    /** @var null|CounterReportFormatter */
    protected static $counterReportFormatter;

    /** @var null|ProfilerReportFormatter */
    protected static $profilerReportFormatter;

    /** @var null|BenchmarkReportFormatter */
    protected static $benchmarkReportFormatter;

    /** @var null|BenchmarkFunctionFormatter */
    protected static $benchmarkFunctionFormatter;

    /** @codeCoverageIgnore */
    private function __construct()
    {
        // Static class
    }

    /**
     * @return TimerReportFormatter
     */
    public static function getTimerReportFormatter(): TimerReportFormatter
    {
        if (null === static::$timerReportFormatter) {
            static::$timerReportFormatter = new TimerReportFormatter();
        }
        return
            static::$timerReportFormatter;
    }

    /**
     * @param null|TimerReportFormatter $timerReportFormatter
     */
    public static function setTimerReportFormatter(?TimerReportFormatter $timerReportFormatter): void
    {
        self::$timerReportFormatter = $timerReportFormatter;
    }

    /**
     * @return CounterReportFormatter
     */
    public static function getCounterReportFormatter(): CounterReportFormatter
    {
        if (null === static::$counterReportFormatter) {
            static::$counterReportFormatter = new CounterReportFormatter();
        }
        return
            new CounterReportFormatter();
    }

    /**
     * @param null|CounterReportFormatter $counterReportFormatter
     */
    public static function setCounterReportFormatter(?CounterReportFormatter $counterReportFormatter): void
    {
        self::$counterReportFormatter = $counterReportFormatter;
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

    /**
     * @param BenchmarkFunctionFormatter $benchmarkFunctionFormatter
     */
    public static function setBenchmarkFunctionFormatter(
        BenchmarkFunctionFormatter $benchmarkFunctionFormatter
    ): void {
        self::$benchmarkFunctionFormatter = $benchmarkFunctionFormatter;
    }

    /**
     * @return BenchmarkReportFormatter
     */
    public static function getBenchmarkReportFormatter(): BenchmarkReportFormatter
    {
        if (null === static::$benchmarkReportFormatter) {
            static::$benchmarkReportFormatter = new BenchmarkReportFormatter();
        }
        return
            new BenchmarkReportFormatter();
    }

    /**
     * @param null|BenchmarkReportFormatter $benchmarkReportFormatter
     */
    public static function setBenchmarkReportFormatter(?BenchmarkReportFormatter $benchmarkReportFormatter): void
    {
        self::$benchmarkReportFormatter = $benchmarkReportFormatter;
    }

    /**
     * @return ProfilerReportFormatter
     */
    public static function getProfilerReportFormatter(): ProfilerReportFormatter
    {
        if (null === static::$profilerReportFormatter) {
            static::$profilerReportFormatter = new ProfilerReportFormatter();
        }
        return
            new ProfilerReportFormatter();
    }

    /**
     * @param null|ProfilerReportFormatter $profilerReportFormatter
     */
    public static function setProfilerReportFormatter(?ProfilerReportFormatter $profilerReportFormatter): void
    {
        self::$profilerReportFormatter = $profilerReportFormatter;
    }
}
