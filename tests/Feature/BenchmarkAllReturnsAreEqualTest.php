<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 15:46
 */

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\Benchmark;
use AlecRabbit\Tools\Reports\BenchmarkReport;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class BenchmarkAllReturnsAreEqualTest extends TestCase
{
    protected const ALL_RETURNS_ARE_EQUAL = 'All returns are equal';
    protected const RETURN_STR = 'integer(1)';

    /** @var Benchmark */
    private $bench;

    /**
     * @test
     * @throws \Exception
     */
    public function showsNotificationOnly(): void
    {
        $this->bench
            ->addFunction(function ($a) {
                usleep(10);
                return $a;
            }, 1);
        $this->bench
            ->addFunction(function ($a) {
                usleep(20);
                return $a;
            }, 1);
        $this->bench
            ->addFunction(function ($a) {
                usleep(30);
                return $a;
            }, 1);
        $this->bench
            ->addFunction(function ($a) {
                usleep(40);
                return $a;
            }, 1);
        $report = $this->bench->report();
        $this->assertTrue($this->bench->isNotShowReturns());
        $this->assertInstanceOf(BenchmarkReport::class, $report);
        $str = (string)$report;
//        echo PHP_EOL . $str . PHP_EOL;
        $this->assertContains(self::ALL_RETURNS_ARE_EQUAL . '.', $str);
        $this->assertNotContains(self::RETURN_STR, $str);
        $this->assertEquals(0, $this->countReturns($str));
    }

    /**
     * @param string $str
     * @return int
     */
    protected function countReturns(string $str): int
    {
        return substr_count($str, self::RETURN_STR);
    }

//    /**
//     * @test
//     * @throws \Exception
//     */
//    public function showsNotificationAndReturnOnce(): void
//    {
//        $this->bench
//            ->addFunction(function ($a) {
//                usleep(10);
//                return $a;
//            }, 1);
//        $this->bench
//            ->addFunction(function ($a) {
//                usleep(20);
//                return $a;
//            }, 1);
//        $this->bench
//            ->addFunction(function ($a) {
//                usleep(30);
//                return $a;
//            }, 1);
//        $this->bench
//            ->addFunction(function ($a) {
//                usleep(40);
//                return $a;
//            }, 1);
//        $report = $this->bench->showReturns()->report();
//        $this->assertInstanceOf(BenchmarkReport::class, $report);
//        $str = (string)$report;
//        echo PHP_EOL . $str . PHP_EOL;
//        $this->assertContains(self::ALL_RETURNS_ARE_EQUAL . ':', $str);
//        $this->assertContains(self::INTEGER_1, $str);
//        $this->assertEquals(1, $this->countReturns($str));
//    }

    /**
     * @throws \Exception
     */
    protected function setUp()
    {
        parent::setUp();
        $this->bench = new Benchmark(100);
    }
}
