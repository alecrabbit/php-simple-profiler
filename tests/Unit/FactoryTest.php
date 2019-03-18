<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Reports\Factory;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Tools\Reports\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\ExtendedCounterReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\Formatter;
use AlecRabbit\Tools\Reports\Formatters\ProfilerReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\SimpleCounterReportFormatter;
use AlecRabbit\Tools\Reports\Formatters\TimerReportFormatter;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider formatterProvider
     * @param Formatter $fromFactory
     * @param Formatter $new
     */
    public function formatterInstance(Formatter $fromFactory, Formatter $new): void
    {
        $this->assertSame($fromFactory, $new);
    }

    /** @test */
    public function setFormatters(): void
    {
        Factory::setBenchmarkFunctionFormatter(null);
        $this->assertNotNull(Factory::getBenchmarkFunctionFormatter());

        Factory::setBenchmarkReportFormatter(null);
        $this->assertNotNull(Factory::getBenchmarkReportFormatter());

        Factory::setExtendedCounterReportFormatter(null);
        $this->assertNotNull(Factory::getExtendedCounterReportFormatter());

        Factory::setProfilerReportFormatter(null);
        $this->assertNotNull(Factory::getProfilerReportFormatter());

        Factory::setSimpleCounterReportFormatter(null);
        $this->assertNotNull(Factory::getSimpleCounterReportFormatter());

        Factory::setTimerReportFormatter(null);
        $this->assertNotNull(Factory::getTimerReportFormatter());
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
                    new class extends SimpleCounterReportFormatter
                    {
                    }
                ),
                Factory::getSimpleCounterReportFormatter(),
            ],
            [
                Factory::setFormatter(
                    new class extends ExtendedCounterReportFormatter
                    {
                    }
                ),
                Factory::getExtendedCounterReportFormatter(),
            ],
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
            [
                Factory::setFormatter(
                    new class extends BenchmarkFunctionFormatter
                    {
                    }
                ),
                Factory::getBenchmarkFunctionFormatter(),
            ],
        ];
    }
}
