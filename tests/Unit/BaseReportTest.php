<?php
/**
 * User: alec
 * Date: 04.12.18
 * Time: 15:35
 */

namespace Tests\Unit;


use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use PHPUnit\Framework\TestCase;

class BaseReportTest extends TestCase
{
    /** @test */
    public function creation(): void
    {
        $obj = new BenchmarkReport(new Benchmark());
//        $this->assertContains('Not implemented!', (string)$obj);
    }
}