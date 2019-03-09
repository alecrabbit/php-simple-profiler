<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\SimpleCounter;
use PHPUnit\Framework\TestCase;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class SimpleCounterTest extends TestCase
{
    /**
     * @test
     * @dataProvider simpleCounterInstanceDataProvider
     * @param array $expected
     * @param array $params
     */
    public function simpleCounterInstance(array $expected, array $params): void
    {
        $c = new SimpleCounter(...$params);
        [$name, $value, $step, $initial] = $expected;
        $this->assertEquals($name, $c->getName());
        $this->assertEquals($value, $c->getValue());
        $this->assertEquals($step, $c->getStep());
        $this->assertEquals($initial, $c->getInitialValue());
        $this->assertEquals(0, $c->getBumped());
        $value += $step;
        $c->bump();
        $this->assertEquals($value, $c->getValue());
        $this->assertEquals(1, $c->getBumped());
    }

    /**
     * @return array
     */
    public function simpleCounterInstanceDataProvider(): array
    {
        $pop = 'pop';
        $name = 'name';
        return [
            // [$name, $value, $step, $initial], [$name, $step, $initial]
            [[DEFAULT_NAME, 0, 1, 0], []],
            [[DEFAULT_NAME, 0, 1, 0], [null]],
            [[DEFAULT_NAME, 1, 1, 1], [null, 1, 1]],
            [[$name, 0, 1, 0], [$name]],
            [[$pop, 0, 1, 0], [$pop]],
            [[$pop, 10, 1, 10], [$pop, 1, 10]],
        ];
    }
}
