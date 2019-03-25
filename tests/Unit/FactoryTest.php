<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Factory;
use AlecRabbit\Tools\Formattable;
use AlecRabbit\Tools\Formatters\BenchmarkFunctionFormatter;
use AlecRabbit\Tools\Formatters\BenchmarkReportFormatter;
use AlecRabbit\Tools\Formatters\Core\Formatter;
use AlecRabbit\Tools\Formatters\ExtendedCounterReportFormatter;
use AlecRabbit\Tools\Formatters\ProfilerReportFormatter;
use AlecRabbit\Tools\Formatters\SimpleCounterReportFormatter;
use AlecRabbit\Tools\Formatters\TimerReportFormatter;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /** @test */
    public function wrongFormatterInstance(): void
    {
        $this->expectException(\RuntimeException::class);
        Factory::setFormatter(
            new class extends Formatter
            {
                public function process(Formattable $formattable): string
                {
                    return '';
                }
            }
        );
    }

    /**
     * @test
     * @dataProvider formatterInstanceProvider
     * @param Formatter $fromFactory
     * @param \AlecRabbit\Tools\Formatters\Core\Formatter $new
     */
    public function formatterInstance(Formatter $new, Formatter $fromFactory): void
    {
        $this->assertSame($fromFactory, $new);
    }

    /**
     * @return array
     */
    public function formatterInstanceProvider(): array
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

    /**
     * @test
     * @dataProvider throughInstanceProvider
     * @param \AlecRabbit\Tools\Formatters\Core\Formatter $new
     */
    public function throughInstance(Formatter $new): void
    {
        $this->assertSame(Factory::setFormatter($new), $new);
    }

    /**
     * @return array
     */
    public function throughInstanceProvider(): array
    {
        return [
            [
                new class extends BenchmarkReportFormatter
                {
                },
            ],
            [
                new class extends SimpleCounterReportFormatter
                {
                },
            ],
            [
                new class extends ExtendedCounterReportFormatter
                {
                },
            ],
            [
                new class extends BenchmarkReportFormatter
                {
                },
            ],
            [
                new class extends TimerReportFormatter
                {
                },
            ],
            [
                new class extends ProfilerReportFormatter
                {
                },
            ],
            [
                new class extends BenchmarkFunctionFormatter
                {
                },
            ],
        ];
    }

    /** @test */
    public function setBenchmarkFunctionFormatter(): void
    {
        Factory::setBenchmarkFunctionFormatter(null);
        $this->assertNotNull(Factory::getBenchmarkFunctionFormatter());
    }

    /** @test */
    public function setBenchmarkReportFormatter(): void
    {
        Factory::setBenchmarkReportFormatter(null);
        $this->assertNotNull(Factory::getBenchmarkReportFormatter());
    }

    /** @test */
    public function setExtendedCounterReportFormatter(): void
    {
        Factory::setExtendedCounterReportFormatter(null);
        $this->assertNotNull(Factory::getExtendedCounterReportFormatter());
    }

    /** @test */
    public function setProfilerReportFormatter(): void
    {
        Factory::setProfilerReportFormatter(null);
        $this->assertNotNull(Factory::getProfilerReportFormatter());
    }

    /** @test */
    public function setSimpleCounterReportFormatter(): void
    {
        Factory::setSimpleCounterReportFormatter(null);
        $this->assertNotNull(Factory::getSimpleCounterReportFormatter());
    }

    /** @test */
    public function setTimerReportFormatter(): void
    {
        Factory::setTimerReportFormatter(null);
        $this->assertNotNull(Factory::getTimerReportFormatter());
    }
}
