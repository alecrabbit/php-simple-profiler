<?php
/**
 * User: alec
 * Date: 04.12.18
 * Time: 15:35
 */

namespace Tests\Unit;


use AlecRabbit\Tools\Reports\Base\Report;
use PHPUnit\Framework\TestCase;

class BaseReportTesting extends Report {

}

class BaseReportTest extends TestCase
{
    /** @test */
    public function creation (): void
    {
        $obj = new BaseReportTesting();
        $this->assertContains('Not implemented!', (string)$obj);
    }
}