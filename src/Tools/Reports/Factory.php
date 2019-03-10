<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
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
     * @return SimpleCounterReportFormatter
     */
    public static function getSimpleCounterReportFormatter(): SimpleCounterReportFormatter
    {
        if (null === static::$simpleCounterReportFormatter) {
            static::$simpleCounterReportFormatter = new SimpleCounterReportFormatter();
        }
        return
            new SimpleCounterReportFormatter();
    }

    /**
     * @param null|SimpleCounterReportFormatter $simpleCounterReportFormatter
     */
    public static function setSimpleCounterReportFormatter(
        ?SimpleCounterReportFormatter $simpleCounterReportFormatter
    ): void {
        self::$simpleCounterReportFormatter = $simpleCounterReportFormatter;
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
            new ExtendedCounterReportFormatter();
    }

    /**
     * @param null|ExtendedCounterReportFormatter $extendedCounterReportFormatter
     */
    public static function setExtendedCounterReportFormatter(
        ?ExtendedCounterReportFormatter $extendedCounterReportFormatter
    ): void {
        self::$extendedCounterReportFormatter = $extendedCounterReportFormatter;
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

    public function setFormatter(FormatterInterface $formatter): void
    {
        if ($formatter instanceof TimerReportFormatter) {
            static::setTimerReportFormatter($formatter);
        }
        if ($formatter instanceof ProfilerReportFormatter) {
            static::setProfilerReportFormatter($formatter);
        }
        throw new \RuntimeException('Formatter [' . typeOf($formatter) .'] is not accepted.');
    }
}
