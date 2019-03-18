<?php
/**
 * User: alec
 * Date: 04.12.18
 * Time: 14:44
 */

namespace Tests\Unit;

use AlecRabbit\Tools\Reports\Factory;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\ProfilerReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\ReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\TimerReportFormatter;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider formatterProvider
     * @param ReportFormatter $fromFactory
     * @param ReportFormatter $new
     */
    public function setFormatter(ReportFormatter $fromFactory, ReportFormatter $new): void
    {
        $this->assertSame($fromFactory, $new);
    }

    public function formatterProvider(): array
    {
        return [
            [
                Factory::setFormatter(
                    new class extends BenchmarkReportFormatter
                    {
                    }
                ),
                Factory::getBenchmarkReportFormatter(),
            ],
            [
                Factory::setFormatter(
                    new class extends TimerReportFormatter
                    {
                    }
                ),
                Factory::getTimerReportFormatter(),
            ],
            [
                Factory::setFormatter(
                    new class extends ProfilerReportFormatter
                    {
                    }
                ),
                Factory::getProfilerReportFormatter(),
            ],
//            [
//                Factory::setFormatter(
//                    new class extends BenchmarkFunctionFormatter
//                    {
//                    }
//                ),
//                Factory::getBenchmarkReportFormatter(),
//            ],
        ];
    }

//    /** @test */
//    public function setFormatter(): void
//    {
//        $formatter =
//            new class extends BenchmarkReportFormatter
//            {
//            };
//        dump(Factory::getBenchmarkReportFormatter());
//        dump(Factory::getBenchmarkReportFormatter());
//        Factory::setFormatter($formatter);
//        $benchmarkReportFormatter = Factory::getBenchmarkReportFormatter();
//        $class = new ReflectionClass(Factory::class);
//        $arr = $class->getStaticProperties();
//        dump($arr, $formatter, $benchmarkReportFormatter);
//        $this->assertSame($formatter, $benchmarkReportFormatter);
//    }
}
