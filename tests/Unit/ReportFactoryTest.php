<?php
/**
 * User: alec
 * Date: 04.12.18
 * Time: 14:44
 */

namespace Tests\Unit;

use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\ReportFactory;
use PHPUnit\Framework\TestCase;

class UnimplementedReport implements ReportableInterface
{
}

class ReportFactoryTest extends TestCase
{
    /** @test */
    public function creation(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->assertInstanceOf(
            UnimplementedReport::class,
            ReportFactory::createReport(new UnimplementedReport())
        );
    }
}