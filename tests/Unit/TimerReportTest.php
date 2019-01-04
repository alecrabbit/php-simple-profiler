<?php
/**
 * User: alec
 * Date: 27.12.18
 * Time: 17:48
 */
declare(strict_types=1);

namespace Tests\Unit;

use AlecRabbit\Tools\Reports\TimerReport;
use AlecRabbit\Tools\Timer;
use PHPUnit\Framework\TestCase;

class TimerReportTest extends TestCase
{
    /** @test */
    public function instantiateWithException(): void
    {
        $timer = new Timer();
        $report = new TimerReport($timer);
        $this->assertInstanceOf(TimerReport::class, $report);
    }
}