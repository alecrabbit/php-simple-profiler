<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Exception\InvalidStyleException;
use AlecRabbit\Themed;
use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Profiler;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\Contracts\ReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\CounterReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\ProfilerReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\TimerReportFormatter;
use AlecRabbit\Tools\Timer;
use function AlecRabbit\typeOf;

class Factory
{
    /** @var Themed */
    protected static $theme;
    /** @var bool */
    protected static $colour = false;

    /**
     * @param ReportableInterface $reportable
     * @return ReportInterface
     * @throws InvalidStyleException
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
     * @return ReportFormatter
     * @throws InvalidStyleException
     */
    public static function makeFormatter(ReportInterface $report): ReportFormatter
    {
        if ($report instanceof TimerReport) {
            return
                new TimerReportFormatter($report);
        }
        if ($report instanceof CounterReport) {
            return
                new CounterReportFormatter($report);
        }
        if ($report instanceof ProfilerReport) {
            return
                new ProfilerReportFormatter($report);
        }
        if ($report instanceof BenchmarkReport) {
            return
                new BenchmarkReportFormatter($report);
        }
        throw new \RuntimeException('Attempt to create unimplemented formatter for: ' . typeOf($report));
    }

    /**
     * @return Themed
     * @throws InvalidStyleException
     */
    public static function getThemedObject(): Themed
    {
        if (null === static::$theme) {
            static::$theme = new Themed(static::$colour);
        }
        return static::$theme;
    }

    /**
     * @param bool $colour
     */
    public static function setColour(bool $colour): void
    {
        self::$colour = $colour;
    }
}
