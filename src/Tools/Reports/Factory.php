<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\Reports\Formatters\ExtendedCounterReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\Formatter;
use AlecRabbit\Tools\Reports\Formatters\ProfilerReportFormatter;
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
     */
    public static function setSimpleCounterReportFormatter(
        ?SimpleCounterReportFormatter $simpleCounterReportFormatter
    ): void {
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
     */
    public static function setExtendedCounterReportFormatter(
        ?ExtendedCounterReportFormatter $extendedCounterReportFormatter
    ): void {
        static::$extendedCounterReportFormatter = $extendedCounterReportFormatter;
    }

    public static function setFormatter(FormatterInterface $formatter): Formatter
    {
        if ($formatter instanceof TimerReportFormatter) {
            static::setTimerReportFormatter($formatter);
            return self::getTimerReportFormatter();
        }
        if ($formatter instanceof ProfilerReportFormatter) {
            static::setProfilerReportFormatter($formatter);
            return self::getProfilerReportFormatter();
        }
        if ($formatter instanceof BenchmarkReportFormatter) {
            static::setBenchmarkReportFormatter($formatter);
            return self::getBenchmarkReportFormatter();
        }
        if ($formatter instanceof BenchmarkFunctionFormatter) {
            static::setBenchmarkFunctionFormatter($formatter);
            return self::getBenchmarkFunctionFormatter();
        }
        throw new \RuntimeException('Formatter [' . typeOf($formatter) . '] is not accepted.');
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
        static::$timerReportFormatter = $timerReportFormatter;
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
     */
    public static function setProfilerReportFormatter(
        ?ProfilerReportFormatter $profilerReportFormatter
    ): void {
        static::$profilerReportFormatter = $profilerReportFormatter;
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
     */
    public static function setBenchmarkReportFormatter(
        ?BenchmarkReportFormatter $benchmarkReportFormatter
    ): void {
        static::$benchmarkReportFormatter = $benchmarkReportFormatter;
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
        static::$benchmarkFunctionFormatter = $benchmarkFunctionFormatter;
    }
}
