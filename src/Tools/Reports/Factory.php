<?php
/**
 * User: alec
 * Date: 01.12.18
 * Time: 17:14
 */

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Counter;
use AlecRabbit\Tools\Internal\Theme;
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
    private static $theme;
    protected static $colour = false;

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
     * @return ReportFormatter
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

    public static function getThemeObject(): Theme
    {
        if (!static::$theme) {
            static::$theme = new Theme(static::$colour);
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
