<?php
/**
 * User: alec
 * Date: 04.12.18
 * Time: 14:44
 */

namespace Tests\Unit;

use AlecRabbit\Tools\Reports\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /** @test */
    public function creationReportOnUnimplemented(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->assertInstanceOf(
            UnimplementedReportable::class,
            Factory::makeReport(new UnimplementedReportable())
        );
    }
    /** @test */
    public function creationFormatterOnUnimplemented(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->assertInstanceOf(
            UnimplementedReport::class,
            Factory::makeFormatter(new UnimplementedReport())
        );
    }
}