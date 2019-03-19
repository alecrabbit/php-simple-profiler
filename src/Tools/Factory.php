<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionSymfonyFormatter;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\ExtendedCounterReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\Formatter;
use AlecRabbit\Tools\Reports\Formatters\ProfilerReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\SimpleCounterReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\TimerReportFormatter;
use Symfony\Component\Console\Helper\ProgressBar;
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

    /** @var int */
    protected static $defaultIterations = 1000000;

    /** @codeCoverageIgnore */
    private function __construct()
    {
        // Static class
    }

    /**
     * @param int $defaultIterations
     */
    public static function setDefaultIterations(int $defaultIterations): void
    {
        self::$defaultIterations = $defaultIterations;
    }

    public static function setFormatter(Formatter $formatter): Formatter
    {
        if ($formatter instanceof SimpleCounterReportFormatter) {
            static::setSimpleCounterReportFormatter($formatter);
            return
                static::getSimpleCounterReportFormatter();
        }
        if ($formatter instanceof ExtendedCounterReportFormatter) {
            static::setExtendedCounterReportFormatter($formatter);
            return
                static::getExtendedCounterReportFormatter();
        }
        if ($formatter instanceof TimerReportFormatter) {
            static::setTimerReportFormatter($formatter);
            return
                static::getTimerReportFormatter();
        }
        if ($formatter instanceof ProfilerReportFormatter) {
            static::setProfilerReportFormatter($formatter);
            return
                static::getProfilerReportFormatter();
        }
        if ($formatter instanceof BenchmarkReportFormatter) {
            static::setBenchmarkReportFormatter($formatter);
            return
                static::getBenchmarkReportFormatter();
        }
        if ($formatter instanceof BenchmarkFunctionFormatter) {
            static::setBenchmarkFunctionFormatter($formatter);
            return
                static::getBenchmarkFunctionFormatter();
        }
        throw new \RuntimeException('Formatter [' . typeOf($formatter) . '] is not accepted.');
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
     * @param null|BenchmarkFunctionFormatter $benchmarkFunctionFormatter
     */
    public static function setBenchmarkFunctionFormatter(
        ?BenchmarkFunctionFormatter $benchmarkFunctionFormatter
    ): void {
        static::$benchmarkFunctionFormatter = $benchmarkFunctionFormatter;
    }

    /**
     * @param null|int $iterations
     * @param bool $withProgressBar
     * @return Benchmark
     * @throws \Exception
     */
    public static function createBenchmark(?int $iterations = null, bool $withProgressBar = true): Benchmark
    {
        // TODO where to set colored formatters?

        $iterations = $iterations ?? static::$defaultIterations;
        if (!$withProgressBar) {
            return
                new Benchmark($iterations);
        }
        if (\class_exists(ProgressBar::class)) {
            static::setFormatter(new BenchmarkFunctionSymfonyFormatter());
            return
                new BenchmarkSymfonyProgressBar($iterations);
        }
        return
            new BenchmarkSimpleProgressBar($iterations);
    }
}
