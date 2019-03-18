<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\Reports\Formatters\ExtendedCounterReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\ProfilerReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\ReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\SimpleCounterReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\TimerReportFormatter;
use function AlecRabbit\typeOf;

class Factory
{
    /** @var null|TimerReportFormatter */
    protected static $timerReportFormatter;

    /** @var null|SimpleCounterReportFormatter */
    protected static $simpleCounterReportFormatter;

    /** @var null|ExtendedCounterReportFormatter */
    protected static $extendedCounterReportFormatter;

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
     * @return TimerReportFormatter
     */
    public static function setTimerReportFormatter(?TimerReportFormatter $timerReportFormatter): TimerReportFormatter
    {
        return
            static::$timerReportFormatter = $timerReportFormatter;
    }

    /**
     * @return SimpleCounterReportFormatter
     */
    public static function getSimpleCounterReportFormatter(): SimpleCounterReportFormatter
    {
        if (null === static::$simpleCounterReportFormatter) {
            static::$simpleCounterReportFormatter = new SimpleCounterReportFormatter();
        }
        return
            static::$simpleCounterReportFormatter;
    }

    /**
     * @param null|SimpleCounterReportFormatter $simpleCounterReportFormatter
     * @return SimpleCounterReportFormatter
     */
    public static function setSimpleCounterReportFormatter(
        ?SimpleCounterReportFormatter $simpleCounterReportFormatter
    ): SimpleCounterReportFormatter {
        return
            static::$simpleCounterReportFormatter = $simpleCounterReportFormatter;
    }

    /**
     * @return ExtendedCounterReportFormatter
     */
    public static function getExtendedCounterReportFormatter(): ExtendedCounterReportFormatter
    {
        if (null === static::$extendedCounterReportFormatter) {
            static::$extendedCounterReportFormatter = new ExtendedCounterReportFormatter();
        }
        return
            static::$extendedCounterReportFormatter;
    }

    /**
     * @param null|ExtendedCounterReportFormatter $extendedCounterReportFormatter
     * @return ExtendedCounterReportFormatter
     */
    public static function setExtendedCounterReportFormatter(
        ?ExtendedCounterReportFormatter $extendedCounterReportFormatter
    ): ExtendedCounterReportFormatter {
        return static::$extendedCounterReportFormatter = $extendedCounterReportFormatter;
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
     * @return BenchmarkFunctionFormatter
     */
    public static function setBenchmarkFunctionFormatter(
        BenchmarkFunctionFormatter $benchmarkFunctionFormatter
    ): BenchmarkFunctionFormatter {
        return static::$benchmarkFunctionFormatter = $benchmarkFunctionFormatter;
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
            static::$benchmarkReportFormatter;
    }

    /**
     * @param null|BenchmarkReportFormatter $benchmarkReportFormatter
     * @return BenchmarkReportFormatter
     */
    public static function setBenchmarkReportFormatter(
        ?BenchmarkReportFormatter $benchmarkReportFormatter
    ): BenchmarkReportFormatter {
        return static::$benchmarkReportFormatter = $benchmarkReportFormatter;
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
            static::$profilerReportFormatter;
    }

    /**
     * @param null|ProfilerReportFormatter $profilerReportFormatter
     * @return ProfilerReportFormatter
     */
    public static function setProfilerReportFormatter(
        ?ProfilerReportFormatter $profilerReportFormatter
    ): ProfilerReportFormatter {
        return
            static::$profilerReportFormatter = $profilerReportFormatter;
    }

    public static function setFormatter(FormatterInterface $formatter): ReportFormatter
    {
        if ($formatter instanceof TimerReportFormatter) {
            return static::setTimerReportFormatter($formatter);
        }
        if ($formatter instanceof ProfilerReportFormatter) {
            return static::setProfilerReportFormatter($formatter);
        }
        if ($formatter instanceof BenchmarkReportFormatter) {
            return static::setBenchmarkReportFormatter($formatter);
        }
        throw new \RuntimeException('Formatter [' . typeOf($formatter) . '] is not accepted.');
    }
}
